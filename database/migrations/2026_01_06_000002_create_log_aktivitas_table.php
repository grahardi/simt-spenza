<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Log kegiatan untuk superadmin - dikelompokkan per kategori (absensi,
     * pelanggaran, keterlambatan, sistem/lainnya) supaya tidak jadi 1 daftar
     * raksasa yang susah dibaca. Contoh isi: "Ginanjar Rahardi menambah
     * absen Fania Zahra jadi Sakit".
     */
    public function up(): void
    {
        Schema::create('log_aktivitas', function (Blueprint $table) {
            $table->id();
            $table->integer('id_member')->nullable();
            $table->string('kategori', 30); // absensi, pelanggaran, keterlambatan, sistem, lainnya
            $table->string('aksi', 255); // deskripsi lengkap kegiatan
            $table->timestamp('created_at')->useCurrent();

            $table->foreign('id_member')->references('id')->on('member')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('log_aktivitas');
    }
};
