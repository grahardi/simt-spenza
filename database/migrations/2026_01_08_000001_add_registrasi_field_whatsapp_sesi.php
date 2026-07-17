<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            // Menyimpan siswa yang SEDANG dikonfirmasi saat alur registrasi
            // (beda dari id_siswa_dipilih yang dipakai alur absen), supaya
            // dua alur ini tidak saling menimpa data satu sama lain.
            $table->unsignedInteger('id_siswa_calon_registrasi')->nullable()->after('jenis_dipilih');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            $table->dropColumn('id_siswa_calon_registrasi');
        });
    }
};
