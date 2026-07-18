<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            // Guru yang SEDANG dikonfirmasi saat alur registrasi guru (fitur
            // tersembunyi 'regis-guru') - terpisah dari field registrasi siswa
            // supaya 2 alur ini tidak saling menimpa.
            $table->integer('id_guru_calon_registrasi')->nullable()->after('foto_sementara');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            $table->dropColumn('id_guru_calon_registrasi');
        });
    }
};
