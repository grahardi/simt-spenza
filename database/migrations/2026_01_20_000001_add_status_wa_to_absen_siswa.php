<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absen_siswa', function (Blueprint $table) {
            // 'terkirim' = piket sudah klik WA Wali Murid, menunggu balasan
            // 'dibalas'  = wali murid sudah balas lewat WA, balasannya
            //              disimpan di kolom `tambahan` (dipakai sebagai
            //              catatan/keterangan sistem)
            $table->enum('status_wa', ['terkirim', 'dibalas'])->nullable()->after('dari_wa');
        });
    }

    public function down(): void
    {
        Schema::table('absen_siswa', function (Blueprint $table) {
            $table->dropColumn('status_wa');
        });
    }
};
