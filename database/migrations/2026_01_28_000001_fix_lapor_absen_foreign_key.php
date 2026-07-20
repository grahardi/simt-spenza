<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Sama seperti kasus keterlambatan sebelumnya - FK lama peninggalan
        // skema PHP native mengarah ke tabel `siswa` (legacy, kosong),
        // seharusnya `datasiswa`. Lepas semua FK id_siswa di lapor_absen.
        $daftarFk = DB::select("
            SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'lapor_absen'
                AND COLUMN_NAME = 'id_siswa' AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        foreach ($daftarFk as $fk) {
            Schema::table('lapor_absen', function (Blueprint $table) use ($fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            });
        }
    }

    public function down(): void
    {
        // Tidak perlu revert - FK lama itu memang salah, tidak boleh dipasang lagi.
    }
};
