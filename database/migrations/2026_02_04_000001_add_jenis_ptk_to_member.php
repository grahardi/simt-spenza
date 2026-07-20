<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            // Klasifikasi PTK (Pendidik dan Tenaga Kependidikan) - langkah awal
            // menuju penyatuan data guru+karyawan ke tabel member (jangka
            // panjang, buat sinkron ke aplikasi lain). 'guru' = pendidik,
            // 'tenaga_administrasi' = PTK non-guru (TU, satpam, dst).
            $table->enum('jenis_ptk', ['guru', 'tenaga_administrasi'])->nullable()->after('jabatan_dinas');
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('jenis_ptk');
        });
    }
};
