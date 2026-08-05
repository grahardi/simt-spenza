<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropColumn('id_penanggung_jawab');
            $table->enum('status_administrasi', ['belum_selesai', 'selesai'])->default('belum_selesai')->after('kategori');
        });

        Schema::create('agenda_penanggung_jawab', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_agenda');
            $table->integer('id_guru');
            $table->enum('jabatan', ['Ketua', 'Sekretaris']);
            $table->timestamps();

            $table->foreign('id_agenda')->references('id')->on('agenda')->cascadeOnDelete();
            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
            $table->unique(['id_agenda', 'jabatan']); // 1 agenda cuma 1 Ketua dan 1 Sekretaris
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_penanggung_jawab');
        Schema::table('agenda', function (Blueprint $table) {
            $table->integer('id_penanggung_jawab')->nullable();
            $table->dropColumn('status_administrasi');
        });
    }
};
