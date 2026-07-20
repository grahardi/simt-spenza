<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendampingan_wali', function (Blueprint $table) {
            $table->id();
            $table->integer('id_guru'); // signed, samakan tipe dengan guru.id_guru
            $table->dateTime('tanggal_waktu');
            $table->string('kategori', 50)->default('Pendampingan'); // baru 1 opsi dulu, bisa ditambah nanti
            $table->string('judul', 150);
            $table->text('deskripsi')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
        });

        // Pivot peserta - siswa mana saja yang ikut 1 sesi pendampingan
        Schema::create('pendampingan_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pendampingan');
            $table->integer('id_siswa');

            $table->foreign('id_pendampingan')->references('id')->on('pendampingan_wali')->cascadeOnDelete();
            $table->foreign('id_siswa')->references('id_member')->on('datasiswa')->cascadeOnDelete();
            $table->unique(['id_pendampingan', 'id_siswa']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendampingan_peserta');
        Schema::dropIfExists('pendampingan_wali');
    }
};
