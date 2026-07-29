<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai')->nullable(); // null = kegiatan 1 hari saja
            $table->text('keterangan')->nullable();
            $table->enum('kategori', ['libur', 'kbm', 'ujian', 'kegiatan'])->default('kegiatan');
            $table->integer('dibuat_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda');
    }
};
