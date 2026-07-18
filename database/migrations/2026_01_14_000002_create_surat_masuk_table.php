<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('surat_masuk', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_surat', 100); // nomor surat DARI pengirim asal (bukan nomor internal kita)
            $table->string('asal_surat', 150); // instansi/orang pengirim
            $table->date('tanggal_surat'); // tanggal tertulis di surat
            $table->date('tanggal_terima'); // tanggal surat diterima sekolah
            $table->string('perihal', 200);
            $table->string('file_scan')->nullable();
            $table->string('disposisi_ke', 100)->nullable(); // diteruskan ke siapa/bagian mana
            $table->text('catatan_disposisi')->nullable();
            $table->enum('status', ['baru', 'diproses', 'selesai'])->default('baru');
            $table->integer('dicatat_oleh')->nullable(); // member.id yang input
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('surat_masuk');
    }
};
