<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kartu_ujian', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa')->unique();
            $table->string('password', 50)->nullable();
            $table->string('ruang', 20)->nullable();
            $table->string('nokursi', 10)->nullable();
            $table->timestamps();
        });

        Schema::create('pengaturan_denah', function (Blueprint $table) {
            $table->id();
            $table->string('ruang', 20)->unique();
            $table->enum('tipe', ['kiri', 'kanan'])->default('kiri');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_denah');
        Schema::dropIfExists('kartu_ujian');
    }
};
