<?php

namespace App\Console\Commands;

use App\Models\AbsenSiswa;
use App\Models\Member;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CekWarningOtomatis extends Command
{
    /**
     * Dijalankan terjadwal (lihat routes/console.php atau Kernel schedule) -
     * cek 2 kondisi dan buat warning untuk wali kelas terkait:
     * 1. Ada siswa Alpha hari ini di kelasnya.
     * 2. Ada siswa yang dalam 7 hari terakhir tercatat tidak masuk >= 3 kali.
     *
     * Warning ini SEKADAR INFORMASI untuk wali kelas (beda dari warning
     * "Kelas Kosong" dari kepsek yang butuh konfirmasi alasan guru).
     */
    protected $signature = 'warning:cek-otomatis';

    protected $description = 'Cek siswa Alpha & sering tidak masuk, buat warning otomatis untuk wali kelas';

    public function handle(): int
    {
        $hariIni = Carbon::today();
        $dibuatAlpha = $this->cekAlphaHariIni($hariIni);
        $dibuatSering = $this->cekSeringTidakMasuk($hariIni);

        $this->info("Selesai: {$dibuatAlpha} warning Alpha, {$dibuatSering} warning sering-tidak-masuk dibuat.");

        return self::SUCCESS;
    }

    private function cekAlphaHariIni(Carbon $hariIni): int
    {
        $dibuat = 0;

        $siswaAlpha = AbsenSiswa::with('siswa')
            ->whereDate('tgl_absen', $hariIni)
            ->where('keterangan', 'a')
            ->get();

        foreach ($siswaAlpha as $absen) {
            $siswa = $absen->siswa;
            if (!$siswa) {
                continue;
            }

            $waliKelas = Member::where('walikelas', $siswa->kelas)->first();
            if (!$waliKelas || !$waliKelas->id_guru) {
                continue;
            }

            $keterangan = "{$siswa->nama_lengkap} (No. Induk {$siswa->id_member}, Kelas {$siswa->kelas}) Alpha hari ini.";

            $sudahAda = Warning::where('id_guru', $waliKelas->id_guru)
                ->whereDate('tgl_warning', $hariIni)
                ->where('kategori', 'Siswa Alpha')
                ->where('keterangan', $keterangan)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Warning::create([
                'id_guru' => $waliKelas->id_guru,
                'tgl_warning' => $hariIni->toDateString(),
                'kategori' => 'Siswa Alpha',
                'keterangan' => $keterangan,
                'kelas' => $siswa->kelas,
                'aksi' => null, // informasi saja, tidak butuh respon wali kelas
            ]);
            $dibuat++;
        }

        return $dibuat;
    }

    private function cekSeringTidakMasuk(Carbon $hariIni): int
    {
        $dibuat = 0;
        $mulai = $hariIni->copy()->subDays(6); // 7 hari terakhir termasuk hari ini

        $rekap = AbsenSiswa::whereBetween('tgl_absen', [$mulai->toDateString(), $hariIni->toDateString()])
            ->selectRaw('id_siswa, count(*) as jumlah')
            ->groupBy('id_siswa')
            ->having('jumlah', '>=', 3)
            ->get();

        foreach ($rekap as $row) {
            $siswa = \App\Models\Siswa::find($row->id_siswa);
            if (!$siswa) {
                continue;
            }

            $waliKelas = Member::where('walikelas', $siswa->kelas)->first();
            if (!$waliKelas || !$waliKelas->id_guru) {
                continue;
            }

            $keterangan = "{$siswa->nama_lengkap} (No. Induk {$siswa->id_member}, Kelas {$siswa->kelas}) tidak masuk {$row->jumlah}x dalam 7 hari terakhir ({$mulai->translatedFormat('d M')} - {$hariIni->translatedFormat('d M Y')}).";

            $sudahAda = Warning::where('id_guru', $waliKelas->id_guru)
                ->whereDate('tgl_warning', $hariIni)
                ->where('kategori', 'Sering Tidak Masuk')
                ->where('keterangan', $keterangan)
                ->exists();

            if ($sudahAda) {
                continue;
            }

            Warning::create([
                'id_guru' => $waliKelas->id_guru,
                'tgl_warning' => $hariIni->toDateString(),
                'kategori' => 'Sering Tidak Masuk',
                'keterangan' => $keterangan,
                'kelas' => $siswa->kelas,
                'aksi' => null,
            ]);
            $dibuat++;
        }

        return $dibuat;
    }
}
