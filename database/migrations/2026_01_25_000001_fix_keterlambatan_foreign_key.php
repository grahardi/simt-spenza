<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Foreign key lama peninggalan skema PHP native mengarah ke tabel
        // `siswa` (legacy, tidak dipakai sistem manapun) - seharusnya ke
        // `datasiswa`. Ini bikin insert Keterlambatan gagal terus.
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'keterlambatan'
                AND COLUMN_NAME = 'id_siswa' AND REFERENCED_TABLE_NAME IS NOT NULL
            LIMIT 1
        ");

        if ($fk) {
            Schema::table('keterlambatan', function (Blueprint $table) use ($fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            });
        }

        // Tidak dibuat FK baru ke datasiswa - kolom kelas legacy datasiswa.id_member
        // sudah cukup diandalkan di level aplikasi (sama seperti absen_siswa
        // yang juga tanpa FK eksplisit), supaya tidak berisiko error serupa lagi.
    }

    public function down(): void
    {
        // Tidak perlu revert - FK lama itu memang salah, tidak boleh dipasang lagi.
    }
};
