<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajuan_absen_guru_piket', function (Blueprint $table) {
            $table->id();
            $table->integer('id_guru');
            $table->date('tanggal');
            $table->enum('status', ['s', 'i', 'd']); // Sakit/Ijin/Dispensasi - bukan Alfa
            $table->string('keterangan')->nullable();
            $table->string('foto')->nullable();
            $table->integer('diajukan_oleh')->nullable();
            $table->integer('diacc_oleh')->nullable();
            $table->timestamp('diacc_at')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuan_absen_guru_piket');
    }
};
