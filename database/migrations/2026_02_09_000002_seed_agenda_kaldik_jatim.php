<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Data awal dari Kalender Pendidikan Provinsi Jawa Timur Tahun Ajaran
        // 2026/2027 (Keputusan Kepala Dinas Pendidikan Provinsi Jawa Timur
        // Nomor: 400.3/2876/101.1/2026) - cuma tanggal-tanggal utama yang
        // sudah dikonfirmasi. Kesiswaan bisa tambah/lengkapi lewat menu Agenda.
        $data = [
            [
                'judul' => 'Hari Pertama Masuk Sekolah - Awal Tahun Ajaran 2026/2027',
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => null,
                'keterangan' => 'Awal Tahun Ajaran 2026/2027 se-Provinsi Jawa Timur.',
                'kategori' => 'kbm',
            ],
            [
                'judul' => 'MPLS (Masa Pengenalan Lingkungan Sekolah)',
                'tanggal_mulai' => '2026-07-13',
                'tanggal_selesai' => '2026-07-15',
                'keterangan' => 'Masa Pengenalan Lingkungan Sekolah bagi peserta didik baru.',
                'kategori' => 'kegiatan',
            ],
            [
                'judul' => 'Libur Semester Ganjil',
                'tanggal_mulai' => '2026-12-26',
                'tanggal_selesai' => '2027-01-02',
                'keterangan' => 'Libur akhir semester 1 Tahun Ajaran 2026/2027.',
                'kategori' => 'libur',
            ],
            [
                'judul' => 'Akhir Tahun Ajaran 2026/2027',
                'tanggal_mulai' => '2027-06-18',
                'tanggal_selesai' => null,
                'keterangan' => 'Akhir Tahun Ajaran untuk sekolah sistem 5 hari belajar (Jumat, 18 Juni 2027). Untuk sekolah 6 hari belajar, akhir TA jatuh Sabtu 19 Juni 2027.',
                'kategori' => 'kbm',
            ],
            [
                'judul' => 'Libur Semester Genap / Libur Akhir Tahun Ajaran',
                'tanggal_mulai' => '2027-06-21',
                'tanggal_selesai' => '2027-07-10',
                'keterangan' => 'Libur akhir semester 2 sekaligus penutup Tahun Ajaran 2026/2027.',
                'kategori' => 'libur',
            ],
        ];

        foreach ($data as $row) {
            $row['created_at'] = now();
            $row['updated_at'] = now();
            DB::table('agenda')->insert($row);
        }
    }

    public function down(): void
    {
        DB::table('agenda')->where('keterangan', 'like', '%Jawa Timur%')->orWhere('keterangan', 'like', '%Tahun Ajaran 2026/2027%')->delete();
    }
};
