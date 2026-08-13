<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bansos_ajuan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->string('kelas'); // disimpan terpisah supaya rekap tetap valid walau siswa pindah kelas nanti
            $table->integer('diajukan_oleh')->nullable();
            $table->string('keterangan')->nullable();
            $table->timestamps();

            $table->unique('id_siswa'); // 1 siswa cuma bisa diajukan sekali
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bansos_ajuan');
    }
};
