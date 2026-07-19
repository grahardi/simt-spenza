<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_template')->insert([
            [
                'kode' => 'absen_sudah_terabsen',
                'keterangan' => 'Absen: siswa sudah tercatat absen resmi hari ini. Placeholder: {nama}, {status}',
                'teks' => "Ananda *{nama}* terdeteksi sudah tercatat *{status}* hari ini.\n\nJika merasa ada kesalahan, silakan hubungi contact person Anda di sekolah.",
                'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'kode' => 'batal_umum',
                'keterangan' => 'Alur dibatalkan user (ketik "batal" di tengah proses absen)',
                'teks' => "Dibatalkan.",
                'created_at' => now(), 'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        DB::table('whatsapp_template')->whereIn('kode', ['absen_sudah_terabsen', 'batal_umum'])->delete();
    }
};
