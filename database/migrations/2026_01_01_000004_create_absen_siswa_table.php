<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Dipetakan langsung dari query absenjelas.php:
    // absen_siswa.id_siswa -> siswa_id, absen_siswa.tgl_absen -> tanggal,
    // keterangan (s/i/a/d), gambar (bukti foto sakit/ijin).
    public function up(): void
    {
        Schema::create('absen_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->constrained('siswa')->cascadeOnDelete();
            $table->date('tanggal');
            $table->enum('keterangan', ['h', 's', 'i', 'a', 'd'])
                ->default('h')
                ->comment('h=hadir, s=sakit, i=ijin, a=alpha, d=dispensasi');
            $table->string('gambar')->nullable()->comment('bukti foto sakit/ijin');
            $table->foreignId('diinput_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['siswa_id', 'tanggal']); // satu siswa hanya 1 absen per hari
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen_siswa');
    }
};
