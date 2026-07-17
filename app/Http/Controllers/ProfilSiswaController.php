<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Keterlambatan;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Carbon\Carbon;

class ProfilSiswaController extends Controller
{
    /**
     * Pengganti tampil_detail.php (index.php?halaman=detail&id=...) -
     * profil lengkap 1 siswa: biodata + riwayat absensi + keterlambatan +
     * pelanggaran.
     */
    public function show(Siswa $siswa)
    {
        $absensi = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->limit(50)
            ->get();

        $keterlambatan = Keterlambatan::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->limit(50)
            ->get();

        $pelanggaran = Pelanggaran::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_pelanggaran')
            ->limit(200)
            ->get()
            ->groupBy(function ($p) {
                // Tahun ajaran: 1 Juli - 30 Juni. Contoh: Maret 2026 -> tahun ajaran 2025
                // (dimulai Juli 2025), Agustus 2026 -> tahun ajaran 2026.
                $tanggal = $p->tgl_pelanggaran;
                $mulai = $tanggal->month >= 7 ? $tanggal->year : $tanggal->year - 1;

                return $mulai.'/'.($mulai + 1);
            });

        // Tahun ajaran SEKARANG selalu jadi tab default, walau belum ada
        // data pelanggaran sama sekali (tampil "tidak ada pelanggaran").
        $sekarang = Carbon::now('Asia/Jakarta');
        $mulaiTahunSekarang = $sekarang->month >= 7 ? $sekarang->year : $sekarang->year - 1;
        $labelTahunSekarang = $mulaiTahunSekarang.'/'.($mulaiTahunSekarang + 1);

        if (!$pelanggaran->has($labelTahunSekarang)) {
            $pelanggaran->put($labelTahunSekarang, collect());
        }

        $pelanggaran = $pelanggaran->sortKeysDesc();

        return view('siswa.profil', compact('siswa', 'absensi', 'keterlambatan', 'pelanggaran'));
    }
}
