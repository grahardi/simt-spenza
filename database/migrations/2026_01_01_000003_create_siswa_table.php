<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Setara tbl_member di sistem lama. Kolom ditebak dari nama_lengkap, jenis_member,
    // jenis_kelamin, whatsapp, foto yang muncul di absenjelas.php & file terkait siswa lain.
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->nullable()->unique();
            $table->string('nama_lengkap');
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('whatsapp', 20)->nullable();
            $table->string('alamat')->nullable();
            $table->string('foto')->nullable();
            $table->date('tanggal_gabung')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
