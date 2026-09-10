<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('soal_upload', function (Blueprint $table) {
            $table->enum('tipe', ['aplikasi', 'cetak'])->default('aplikasi')->after('mapel');
        });

        // Unique lama (kelas, mapel) tidak cukup lagi karena sekarang 1 kelas+mapel
        // bisa punya 2 baris (aplikasi & cetak) - ganti jadi unique (kelas, mapel, tipe).
        Schema::table('soal_upload', function (Blueprint $table) {
            $table->dropUnique(['kelas', 'mapel']);
            $table->unique(['kelas', 'mapel', 'tipe']);
        });
    }

    public function down(): void
    {
        Schema::table('soal_upload', function (Blueprint $table) {
            $table->dropUnique(['kelas', 'mapel', 'tipe']);
            $table->unique(['kelas', 'mapel']);
            $table->dropColumn('tipe');
        });
    }
};
