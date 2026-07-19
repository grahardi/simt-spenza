<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_template')->where('kode', 'absen_pilih_jenis')->update([
            'teks' => "Ananda *{nama}* ({kelas})\n\nAjukan apa hari ini?\nBalas *1* untuk Sakit\nBalas *2* untuk Ijin\n\n(ketik *batal* untuk keluar)",
        ]);

        DB::table('whatsapp_template')->where('kode', 'minta_selfie')->update([
            'teks' => "Baik, diajukan *{jenis}*.\n\nLangkah 1/2: kirim *foto selfie wajah* Ananda sekarang (untuk verifikasi).\n\n(ketik *batal* untuk keluar)",
        ]);

        DB::table('whatsapp_template')->where('kode', 'selfie_diterima_minta_surat')->update([
            'teks' => "Foto selfie diterima \xE2\x9C\x85\n\nLangkah 2/2: kirim *foto surat keterangan* (surat dokter/surat orang tua) sekarang.\n\n(ketik *batal* untuk keluar)",
        ]);
    }

    public function down(): void
    {
        // Tidak perlu revert teks - biar tidak menimpa perubahan manual admin lewat panel.
    }
};
