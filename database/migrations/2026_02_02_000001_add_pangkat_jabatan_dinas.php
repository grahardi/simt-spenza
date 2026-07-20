<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            // "jabatan dinas" beda dari kolom `jabatan` yang sudah ada (itu
            // dipakai buat penanda role sistem, misal "Superadmin"). Ini
            // khusus buat jabatan resmi kepegawaian, dipakai di surat dinas.
            $table->string('pangkat', 100)->nullable()->after('jabatan');
            $table->string('jabatan_dinas', 100)->nullable()->after('pangkat');
        });

        Schema::table('pengaturan_surat', function (Blueprint $table) {
            // Identitas Kepala Sekolah - dipakai buat tanda tangan surat dinas
            $table->string('kepsek_nama', 100)->nullable();
            $table->string('kepsek_nip', 50)->nullable();
            $table->string('kepsek_pangkat', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn(['pangkat', 'jabatan_dinas']);
        });
        Schema::table('pengaturan_surat', function (Blueprint $table) {
            $table->dropColumn(['kepsek_nama', 'kepsek_nip', 'kepsek_pangkat']);
        });
    }
};
