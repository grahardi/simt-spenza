<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_template')->insert([
            [
                'kode' => 'registrasi_sudah_ada',
                'keterangan' => 'Registrasi: nomor ini sudah pernah terhubung ke siswa ini. Placeholder: {nama}',
                'teks' => "Nomor ini sudah terhubung dengan *{nama}* sebelumnya.\n\nKetik *absen* kapan saja untuk mengajukan Sakit/Ijin.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'kode' => 'registrasi_maksimal',
                'keterangan' => 'Registrasi: siswa sudah punya nomor terdaftar maksimal. Placeholder: {nama}, {maksimal}',
                'teks' => "Mohon maaf, *{nama}* sudah punya {maksimal} nomor WhatsApp terdaftar (maksimal).\n\nHubungi pihak sekolah kalau perlu ganti/hapus salah satu nomor.",
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('whatsapp_template')->whereIn('kode', ['registrasi_sudah_ada', 'registrasi_maksimal'])->delete();
    }
};
