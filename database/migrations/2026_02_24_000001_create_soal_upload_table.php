<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soal_upload', function (Blueprint $table) {
            $table->id();
            $table->string('kelas', 5); // '7', '8', atau '9' (tingkat, bukan kelas spesifik A-J)
            $table->string('mapel', 100);
            $table->string('path'); // path file docx yang aktif sekarang
            $table->integer('id_guru')->nullable();
            $table->timestamps();

            $table->unique(['kelas', 'mapel']); // 1 file aktif per kombinasi kelas+mapel
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soal_upload');
    }
};
