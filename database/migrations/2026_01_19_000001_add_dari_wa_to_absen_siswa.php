<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_siswa', function (Blueprint $table) {
            $table->boolean('dari_wa')->default(false)->after('gambar');
        });
    }

    public function down(): void
    {
        Schema::table('absen_siswa', function (Blueprint $table) {
            $table->dropColumn('dari_wa');
        });
    }
};
