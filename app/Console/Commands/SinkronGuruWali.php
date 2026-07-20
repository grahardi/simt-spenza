<?php

namespace App\Console\Commands;

use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Console\Command;

class SinkronGuruWali extends Command
{
    /**
     * php artisan wali:sinkron           -> cocokkan & tampilkan laporan (aman, tidak ubah data)
     * php artisan wali:sinkron --apply   -> setelah dicek laporannya, isi datasiswa.id_guru_wali beneran
     *
     * Referensi database/data/wali_siswa_reference.php isinya [nis => nama guru wali],
     * dicocokkan ke tabel guru lewat NAMA (bukan kode angka seperti jadwal) -
     * jadi butuh normalisasi nama (spasi/gelar) supaya match rate bagus.
     */
    protected $signature = 'wali:sinkron {--apply : Simpan hasil ke datasiswa.id_guru_wali, bukan cuma pratinjau}';

    protected $description = 'Cocokkan & isi data Guru Wali per siswa dari database/data/wali_siswa_reference.php';

    public function handle(): int
    {
        $referensi = require database_path('data/wali_siswa_reference.php');

        if (empty($referensi)) {
            $this->warn('database/data/wali_siswa_reference.php masih kosong - belum ada yang bisa disinkronkan.');
            return self::SUCCESS;
        }

        $daftarGuru = Guru::all(['id_guru', 'nama']);

        $cocok = 0;
        $tidakCocokNama = [];
        $tidakCocokSiswa = [];
        $update = [];

        foreach ($referensi as $nis => $namaGuruExcel) {
            $siswa = Siswa::find($nis);
            if (!$siswa) {
                $tidakCocokSiswa[] = $nis;
                continue;
            }

            $target = $this->normalisasi($namaGuruExcel);
            $guruCocok = null;
            $skorTerbaik = 0;

            foreach ($daftarGuru as $g) {
                similar_text($target, $this->normalisasi($g->nama), $persen);
                if ($persen > $skorTerbaik) {
                    $skorTerbaik = $persen;
                    $guruCocok = $g;
                }
            }

            if ($guruCocok && $skorTerbaik >= 85) {
                $cocok++;
                $update[$nis] = $guruCocok->id_guru;
            } else {
                $tidakCocokNama[$namaGuruExcel] = ($tidakCocokNama[$namaGuruExcel] ?? 0) + 1;
            }
        }

        $this->info("Total referensi: ".count($referensi));
        $this->info("Cocok (siap diisi): {$cocok}");

        if ($tidakCocokSiswa) {
            $this->warn('NIS tidak ditemukan di tabel siswa: '.implode(', ', array_slice($tidakCocokSiswa, 0, 20)).(count($tidakCocokSiswa) > 20 ? ' ...' : ''));
        }

        if ($tidakCocokNama) {
            $this->warn('Nama guru wali TIDAK ketemu cocok di tabel guru (>=85%):');
            foreach ($tidakCocokNama as $nama => $jumlah) {
                $this->line("  - \"{$nama}\" ({$jumlah} siswa)");
            }
        }

        if (!$this->option('apply')) {
            $this->line('');
            $this->info('Ini baru PRATINJAU. Jalankan dengan --apply untuk benar-benar menyimpan.');
            return self::SUCCESS;
        }

        foreach ($update as $nis => $idGuru) {
            Siswa::where('id_member', $nis)->update(['id_guru_wali' => $idGuru]);
        }

        $this->info("Selesai - {$cocok} siswa berhasil diisi id_guru_wali.");

        return self::SUCCESS;
    }

    private function normalisasi(string $nama): string
    {
        $nama = strtolower($nama);
        $nama = preg_replace('/[.,]/', '', $nama); // hapus titik/koma (gelar S.Pd, M.Pd, dll)
        $nama = preg_replace('/\s+/', ' ', $nama);

        return trim($nama);
    }
}
