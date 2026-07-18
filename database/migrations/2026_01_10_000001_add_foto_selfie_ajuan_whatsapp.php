<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ajuan_whatsapp', function (Blueprint $table) {
            $table->string('foto_selfie')->nullable()->after('foto_surat');
        });

        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            // Nyimpen path foto selfie SEMENTARA selagi nunggu foto surat
            // dikirim juga (2 foto terpisah, dikirim bertahap 1 per pesan WA).
            $table->string('foto_sementara')->nullable()->after('id_siswa_calon_registrasi');
        });
    }

    public function down(): void
    {
        Schema::table('ajuan_whatsapp', function (Blueprint $table) {
            $table->dropColumn('foto_selfie');
        });

        Schema::table('whatsapp_sesi', function (Blueprint $table) {
            $table->dropColumn('foto_sementara');
        });
    }
};
