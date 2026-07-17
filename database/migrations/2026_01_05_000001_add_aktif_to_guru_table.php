<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Superadmin butuh "nonaktifkan guru" (bukan hapus, supaya data historis
     * seperti jadwal/absensi/pelanggaran yang mengacu ke guru itu tidak
     * rusak). Kolom `status` yang sudah ada dipakai untuk status kepegawaian
     * (PNS/Honorer/dst), jadi kolom baru ini khusus status aktif/nonaktif.
     */
    public function up(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->boolean('aktif')->default(true)->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('guru', function (Blueprint $table) {
            $table->dropColumn('aktif');
        });
    }
};
