<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            // Kode umum AKTUAL yang dipakai di nomor surat - defaultnya sama
            // dengan kode kategori (diisi otomatis lewat JS di form), tapi
            // bisa diedit manual (misal tambah sub-kode "400.1") tanpa
            // mengubah data master kategorinya.
            $table->string('kode_umum', 30)->nullable()->after('id_kategori_surat');
        });
    }

    public function down(): void
    {
        Schema::table('surat_keluar', function (Blueprint $table) {
            $table->dropColumn('kode_umum');
        });
    }
};
