<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('nip', 30)->nullable()->unique();
            $table->string('jabatan')->nullable(); // guru mapel, wali kelas, BK, tatib, dst
            $table->string('mapel')->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('foto_profil')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};
