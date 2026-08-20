<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pelanggaran_keagamaan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa');
            $table->integer('jumlah_kabur_saat_tindak'); // total kabur siswa ini pas ditindak - dipakai buat cek kelipatan 3
            $table->text('keterangan');
            $table->integer('ditindak_oleh')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggaran_keagamaan_tindakan');
    }
};
