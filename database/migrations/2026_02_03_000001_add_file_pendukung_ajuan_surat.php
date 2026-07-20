<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ajuan_surat', function (Blueprint $table) {
            // Berkas pendukung (undangan, surat penunjukan, dll) - terpisah
            // dari kolom `data` JSON karena ini file, bukan teks isian.
            $table->string('file_pendukung')->nullable()->after('data');
        });
    }

    public function down(): void
    {
        Schema::table('ajuan_surat', function (Blueprint $table) {
            $table->dropColumn('file_pendukung');
        });
    }
};
