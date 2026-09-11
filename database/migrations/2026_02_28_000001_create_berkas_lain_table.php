<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('berkas_lain', function (Blueprint $table) {
            $table->id();
            $table->string('keterangan'); // diisi manual bebas, misal "Berkas Admin", "SK Panitia", dll
            $table->string('nama_file_asli');
            $table->string('path');
            $table->integer('diupload_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('berkas_lain');
    }
};
