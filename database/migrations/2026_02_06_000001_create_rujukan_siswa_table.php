<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rujukan_siswa', function (Blueprint $table) {
            $table->id();
            $table->integer('id_siswa');
            $table->enum('jenis', ['walikelas', 'bk']); // siapa yang saat ini harus menindaklanjuti
            $table->text('alasan');
            $table->enum('status', ['menunggu', 'selesai'])->default('menunggu');
            $table->enum('tindak_lanjut', ['konfirmasi', 'hubungi_ortu', 'ajukan_bk', 'ajukan_tatib'])->nullable();
            $table->text('catatan_tindak_lanjut')->nullable();
            $table->integer('dilaporkan_oleh')->nullable(); // member.id (tatib yang lapor)
            $table->integer('ditindak_oleh')->nullable(); // member.id (wali kelas/BK yang tindak lanjut)
            $table->timestamp('ditindak_at')->nullable();
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_member')->on('datasiswa')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rujukan_siswa');
    }
};
