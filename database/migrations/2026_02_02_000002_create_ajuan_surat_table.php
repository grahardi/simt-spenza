<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ajuan_surat', function (Blueprint $table) {
            $table->id();
            $table->integer('id_guru'); // signed, samakan tipe dengan guru.id_guru
            $table->string('jenis_surat', 30)->default('sppd'); // baru 1 jenis dulu, gampang ditambah
            $table->json('data'); // field-field spesifik jenis surat (fleksibel per jenis)
            $table->enum('status', ['menunggu', 'diproses', 'selesai'])->default('menunggu');
            $table->string('nomor_surat', 100)->nullable();
            $table->string('file_pdf')->nullable();
            $table->integer('diproses_oleh')->nullable(); // member.id (staf TU)
            $table->timestamp('diproses_at')->nullable();
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuan_surat');
    }
};
