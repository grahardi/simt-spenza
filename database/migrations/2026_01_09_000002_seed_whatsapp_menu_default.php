<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_menu')->insert([
            [
                'kode' => 'registrasi',
                'label' => 'hubungkan nomor ini dengan data anak',
                'tipe' => 'bawaan',
                'balasan' => null,
                'urutan' => 1,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'absen',
                'label' => 'ajukan Sakit/Ijin',
                'tipe' => 'bawaan',
                'balasan' => null,
                'urutan' => 2,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'info',
                'label' => 'info seputar bot ini',
                'tipe' => 'info',
                'balasan' => "Bot ini dipakai untuk:\n1. *Registrasi* - menghubungkan nomor WhatsApp Bapak/Ibu dengan data anak di sekolah (pakai Nomor Induk siswa)\n2. *Absen* - mengajukan Sakit/Ijin untuk anak yang sudah terhubung, lengkap dengan foto surat keterangan\n\nKetik *registrasi* atau *absen* untuk mulai.",
                'urutan' => 3,
                'aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('whatsapp_menu')->whereIn('kode', ['registrasi', 'absen', 'info'])->delete();
    }
};
