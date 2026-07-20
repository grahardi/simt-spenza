<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absensi_guru', function (Blueprint $table) {
            $table->id();
            $table->integer('id_guru'); // signed, samakan tipe dengan guru.id_guru
            $table->date('tanggal');
            $table->enum('status', ['s', 'i', 'a', 'd']); // sakit/ijin/alfa/dispensasi
            $table->string('keterangan', 255)->nullable();
            $table->integer('dicatat_oleh')->nullable(); // member.id
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
            $table->unique(['id_guru', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absensi_guru');
    }
};
