<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Libur nasional & cuti bersama Indonesia, periode tahun ajaran
        // 2026/2027 (Juli 2026 - Juni 2027). Sumber: SKB 3 Menteri (Menteri
        // Agama, Menaker, MenPANRB) Nomor 1497/2/5 Tahun 2025 untuk tahun
        // 2026 (SUDAH RESMI). Untuk tahun 2027, SKB belum diterbitkan
        // (biasanya baru rilis akhir 2026) - tanggal di bawah untuk periode
        // 2027 masih PERKIRAAN berdasarkan pola tahun sebelumnya, sudah
        // ditandai di keterangan masing-masing, Kesiswaan perlu cek ulang
        // begitu SKB 2027 resmi terbit.
        $data = [
            // 2026 (semester ganjil, sisa tahun kalender 2026) - RESMI
            ['judul' => 'Hari Kemerdekaan Republik Indonesia', 'tanggal_mulai' => '2026-08-17', 'keterangan' => 'Peringatan HUT Kemerdekaan RI ke-81. (Resmi - SKB 3 Menteri 2026)'],
            ['judul' => 'Maulid Nabi Muhammad SAW', 'tanggal_mulai' => '2026-08-25', 'keterangan' => 'Hari libur keagamaan (Islam). (Resmi - SKB 3 Menteri 2026)'],
            ['judul' => 'Cuti Bersama Hari Raya Natal', 'tanggal_mulai' => '2026-12-24', 'keterangan' => 'Cuti bersama menjelang Natal. (Resmi - SKB 3 Menteri 2026)'],
            ['judul' => 'Hari Raya Natal', 'tanggal_mulai' => '2026-12-25', 'keterangan' => 'Hari libur keagamaan (Kristen/Katolik). (Resmi - SKB 3 Menteri 2026)'],

            // 2027 (semester genap) - MASIH PERKIRAAN, SKB 2027 belum terbit
            ['judul' => 'Tahun Baru Masehi', 'tanggal_mulai' => '2027-01-01', 'keterangan' => 'Tahun Baru 2027. (Perkiraan - SKB 3 Menteri 2027 belum terbit, biasanya rilis akhir 2026)'],
            ['judul' => "Isra' Mi'raj Nabi Muhammad SAW", 'tanggal_mulai' => '2027-01-05', 'keterangan' => 'Hari libur keagamaan (Islam). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Tahun Baru Imlek', 'tanggal_mulai' => '2027-02-06', 'keterangan' => 'Tahun Baru Imlek 2578 Kongzili. (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Raya Nyepi', 'tanggal_mulai' => '2027-03-09', 'keterangan' => 'Tahun Baru Saka 1949 (Hindu). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Raya Idul Fitri 1448 H', 'tanggal_mulai' => '2027-03-10', 'tanggal_selesai' => '2027-03-11', 'keterangan' => 'Libur Lebaran. (Perkiraan - SKB 2027 belum terbit, tanggal bisa berubah tergantung penetapan hisab/rukyat)'],
            ['judul' => 'Wafat Yesus Kristus (Jumat Agung)', 'tanggal_mulai' => '2027-03-26', 'keterangan' => 'Hari libur keagamaan (Kristen/Katolik). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Kebangkitan Yesus Kristus (Paskah)', 'tanggal_mulai' => '2027-03-28', 'keterangan' => 'Hari libur keagamaan (Kristen/Katolik). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Buruh Internasional', 'tanggal_mulai' => '2027-05-01', 'keterangan' => 'May Day. (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Kenaikan Yesus Kristus', 'tanggal_mulai' => '2027-05-06', 'keterangan' => 'Hari libur keagamaan (Kristen/Katolik). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Raya Idul Adha 1448 H', 'tanggal_mulai' => '2027-05-16', 'keterangan' => 'Hari libur keagamaan (Islam). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Raya Waisak', 'tanggal_mulai' => '2027-05-20', 'keterangan' => 'Hari libur keagamaan (Buddha). (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Hari Lahir Pancasila', 'tanggal_mulai' => '2027-06-01', 'keterangan' => 'Peringatan Hari Lahir Pancasila. (Perkiraan - SKB 2027 belum terbit)'],
            ['judul' => 'Tahun Baru Islam 1449 H', 'tanggal_mulai' => '2027-06-06', 'keterangan' => 'Hari libur keagamaan (Islam). (Perkiraan - SKB 2027 belum terbit)'],
        ];

        foreach ($data as $row) {
            DB::table('agenda')->insert([
                'judul' => $row['judul'],
                'tanggal_mulai' => $row['tanggal_mulai'],
                'tanggal_selesai' => $row['tanggal_selesai'] ?? null,
                'keterangan' => $row['keterangan'],
                'kategori' => 'libur',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('agenda')->where('kategori', 'libur')
            ->whereIn('judul', [
                'Hari Kemerdekaan Republik Indonesia', 'Maulid Nabi Muhammad SAW', 'Cuti Bersama Hari Raya Natal', 'Hari Raya Natal',
                'Tahun Baru Masehi', "Isra' Mi'raj Nabi Muhammad SAW", 'Tahun Baru Imlek', 'Hari Raya Nyepi', 'Hari Raya Idul Fitri 1448 H',
                'Wafat Yesus Kristus (Jumat Agung)', 'Kebangkitan Yesus Kristus (Paskah)', 'Hari Buruh Internasional',
                'Kenaikan Yesus Kristus', 'Hari Raya Idul Adha 1448 H', 'Hari Raya Waisak', 'Hari Lahir Pancasila', 'Tahun Baru Islam 1449 H',
            ])->delete();
    }
};
