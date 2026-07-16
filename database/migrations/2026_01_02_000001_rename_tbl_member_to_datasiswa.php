<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Meluruskan penamaan lama: kolom `tanggal_gabung` ternyata isinya NISN
     * (contoh nilai: '0123123123'), dan `jenis_member` isinya nama kelas
     * (contoh: '9 - A', 'OUT' untuk siswa lulus/keluar). Migration ini
     * merename tabel & kolom supaya nama cocok dengan isinya, tanpa
     * mengubah tipe data atau menghapus apapun.
     */
    public function up(): void
    {
        Schema::rename('tbl_member', 'datasiswa');

        Schema::table('datasiswa', function (Blueprint $table) {
            $table->renameColumn('tanggal_gabung', 'nisn');
            $table->renameColumn('jenis_member', 'kelas');
        });
    }

    public function down(): void
    {
        Schema::table('datasiswa', function (Blueprint $table) {
            $table->renameColumn('nisn', 'tanggal_gabung');
            $table->renameColumn('kelas', 'jenis_member');
        });

        Schema::rename('datasiswa', 'tbl_member');
    }
};
