<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_menu', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 30)->unique(); // kata yang harus diketik PERSIS (case-insensitive)
            $table->string('label'); // teks penjelasan singkat di daftar menu
            $table->enum('tipe', ['bawaan', 'info'])->default('info');
            // 'bawaan' = alur terprogram (registrasi/absen, logikanya di kode, tidak bisa dihapus)
            // 'info'   = balasan teks statis biasa, bebas ditambah/diubah/dihapus superadmin
            $table->text('balasan')->nullable(); // dipakai kalau tipe = info
            $table->unsignedInteger('urutan')->default(0);
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_menu');
    }
};
