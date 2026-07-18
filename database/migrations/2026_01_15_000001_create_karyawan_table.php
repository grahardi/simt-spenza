<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('karyawan', function (Blueprint $table) {
            $table->id('id_karyawan');
            $table->string('nip', 40)->nullable();
            $table->string('nama', 70);
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable();
            $table->string('jabatan', 50)->nullable(); // contoh: Tata Usaha, Satpam, Pustakawan, Petugas Kebersihan
            $table->string('status', 40)->nullable(); // contoh: PNS, Honorer, Kontrak
            $table->string('alamat', 100)->nullable();
            $table->string('telepon', 50)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->boolean('aktif')->default(true);
            $table->timestamps();
        });

        Schema::table('member', function (Blueprint $table) {
            // Menghubungkan akun login ke data karyawan (non-guru), mirip
            // pola id_guru yang sudah ada. Akun Tata Usaha & staf lain yang
            // sebelumnya tidak terhubung ke data apapun, sekarang bisa
            // dihubungkan ke sini.
            $table->unsignedBigInteger('id_karyawan')->nullable()->after('id_guru');
            $table->foreign('id_karyawan')->references('id_karyawan')->on('karyawan')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropForeign(['id_karyawan']);
            $table->dropColumn('id_karyawan');
        });
        Schema::dropIfExists('karyawan');
    }
};
