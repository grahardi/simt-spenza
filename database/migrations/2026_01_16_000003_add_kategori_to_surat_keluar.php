<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->unsignedBigInteger('id_kategori_surat')->nullable()->after('kode_surat');
            $table->foreign('id_kategori_surat')->references('id')->on('kategori_surat')->nullOnDelete();
            $table->unique('nomor_urut');
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropForeign(['id_kategori_surat']);
            $table->dropUnique(['nomor_urut']);
            $table->dropColumn('id_kategori_surat');
        });
    }
};
