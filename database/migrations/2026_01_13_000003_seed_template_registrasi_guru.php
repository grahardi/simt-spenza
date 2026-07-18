<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_template')->insert([
            [
                'kode' => 'registrasi_guru_prompt',
                'keterangan' => 'Registrasi guru (fitur tersembunyi): minta Kode Guru',
                'teks' => "Silakan ketik *Kode Guru* Bapak/Ibu.\n\nKetik *batal* untuk membatalkan.",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'registrasi_guru_tidak_ditemukan',
                'keterangan' => 'Registrasi guru: Kode Guru tidak ditemukan. Placeholder: {kode}',
                'teks' => "Kode Guru *{kode}* tidak ditemukan. Coba periksa lagi, atau ketik *batal*.",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'registrasi_guru_konfirmasi',
                'keterangan' => 'Registrasi guru: konfirmasi data ditemukan. Placeholder: {nama}, {mapel}',
                'teks' => "Ditemukan: *{nama}* - Mapel {mapel}\n\nApakah benar ini Bapak/Ibu? Balas *YA* untuk menghubungkan nomor ini, atau *TIDAK* untuk batal.",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'registrasi_guru_berhasil',
                'keterangan' => 'Registrasi guru: berhasil terhubung. Placeholder: {nama}',
                'teks' => "\xE2\x9C\x85 Berhasil! Nomor WhatsApp ini terhubung dengan *{nama}*.\n\nKetik *jadwal* untuk lihat jadwal mengajar hari ini.",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'jadwal_guru_kosong',
                'keterangan' => 'Jadwal guru: tidak ada jadwal mengajar hari ini. Placeholder: {hari}',
                'teks' => "Tidak ada jadwal mengajar untuk Bapak/Ibu hari ini ({hari}).",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'jadwal_guru_belum_registrasi',
                'keterangan' => 'Jadwal guru: nomor belum terhubung ke guru manapun',
                'teks' => "Nomor ini belum terhubung dengan data guru manapun.",
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('whatsapp_template')->whereIn('kode', [
            'registrasi_guru_prompt', 'registrasi_guru_tidak_ditemukan', 'registrasi_guru_konfirmasi',
            'registrasi_guru_berhasil', 'jadwal_guru_kosong', 'jadwal_guru_belum_registrasi',
        ])->delete();
    }
};
