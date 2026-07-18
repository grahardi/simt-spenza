<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_keluar', function (Blueprint $table) {
            $table->id();
            $table->string('kode_surat', 100)->unique(); // nomor surat resmi, auto-generate
            $table->unsignedInteger('nomor_urut'); // dipakai buat hitung urutan per tahun
            $table->unsignedSmallInteger('tahun');
            $table->date('tanggal_surat');
            $table->string('tujuan_surat', 150);
            $table->string('perihal', 200);
            $table->string('lampiran')->nullable();
            $table->integer('dibuat_oleh')->nullable(); // member.id
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_keluar');
    }
};
