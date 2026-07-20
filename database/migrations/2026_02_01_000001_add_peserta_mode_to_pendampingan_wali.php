<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pendampingan_wali', function (Blueprint $table) {
            $table->enum('peserta_mode', ['semua', 'pilih'])->default('semua')->after('visibilitas');
        });
    }

    public function down(): void
    {
        Schema::table('pendampingan_wali', function (Blueprint $table) {
            $table->dropColumn('peserta_mode');
        });
    }
};
