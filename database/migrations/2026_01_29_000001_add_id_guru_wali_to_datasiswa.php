<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('datasiswa', function (Blueprint $table) {
            // Tanpa FK eksplisit (belajar dari kasus keterlambatan/lapor_absen
            // sebelumnya) - cukup diandalkan di level aplikasi.
            $table->integer('id_guru_wali')->nullable()->after('kelas');
        });
    }

    public function down(): void
    {
        Schema::table('datasiswa', function (Blueprint $table) {
            $table->dropColumn('id_guru_wali');
        });
    }
};
