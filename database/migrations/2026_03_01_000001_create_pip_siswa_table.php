<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pip_siswa', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_siswa')->nullable(); // null kalau NISN tidak ketemu siswanya
            $table->string('nisn', 20)->nullable();
            $table->string('nama_pd')->nullable(); // nama asli dari file Excel, buat referensi/pencocokan
            $table->string('kelas_excel', 10)->nullable();

            $table->decimal('nominal', 15, 2)->nullable();
            $table->string('no_rekening', 50)->nullable();
            $table->string('tahap_id', 20)->nullable();
            $table->string('nomor_sk', 100)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('nama_rekening')->nullable();
            $table->date('tanggal_cair')->nullable();
            $table->string('status_cair', 50)->nullable();
            $table->string('no_kip', 50)->nullable();
            $table->string('no_kks', 50)->nullable();
            $table->string('no_kps', 50)->nullable();
            $table->string('virtual_acc', 50)->nullable();
            $table->string('nama_kartu')->nullable();
            $table->string('semester_id', 20)->nullable();
            $table->string('layak_pip', 10)->nullable();
            $table->text('keterangan_pencairan')->nullable();
            $table->text('confirmation_text')->nullable();
            $table->string('tahap_keterangan')->nullable();
            $table->string('nama_pengusul')->nullable();

            $table->timestamps();

            $table->index('id_siswa');
            $table->index('nisn');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pip_siswa');
    }
};
