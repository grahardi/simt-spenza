<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('uks_kunjungan', function (Blueprint $table) {
            $table->id();
            $table->integer('id_siswa'); // signed, samakan dengan datasiswa.id_member
            $table->text('keterangan_sakit')->nullable();
            $table->enum('status', ['di_uks', 'kembali_kelas', 'pulang_dijemput', 'puskesmas', 'lainnya'])->default('di_uks');
            $table->text('keterangan_penanganan')->nullable();
            $table->date('tanggal');
            $table->timestamp('waktu_masuk')->useCurrent();
            $table->timestamp('waktu_selesai')->nullable();
            $table->integer('dicatat_oleh')->nullable(); // member.id
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_member')->on('datasiswa')->cascadeOnDelete();
            $table->index(['status', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('uks_kunjungan');
    }
};
