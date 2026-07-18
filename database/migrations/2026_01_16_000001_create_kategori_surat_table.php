<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 20); // "Kode Umum", contoh: 400, 421, 800
            $table->string('nama', 100); // contoh: "Kesiswaan", "Surat Keterangan"
            $table->string('keterangan', 200)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_surat');
    }
};
