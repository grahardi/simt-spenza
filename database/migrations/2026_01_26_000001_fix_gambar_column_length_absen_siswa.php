<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Pakai raw SQL (bukan Schema::change()) supaya tidak perlu package
        // doctrine/dbal yang belum tentu terpasang.
        DB::statement('ALTER TABLE absen_siswa MODIFY gambar VARCHAR(255) NULL');

        // Tabel lapor_absen (Ajuan Absensi) punya masalah serupa - kolom
        // peninggalan lama sama-sama kekecilan buat path Laravel.
        DB::statement('ALTER TABLE lapor_absen MODIFY gambar VARCHAR(255) NULL');
        DB::statement('ALTER TABLE lapor_absen MODIFY gambarwali VARCHAR(255) NULL');

        DB::statement('ALTER TABLE datasiswa MODIFY foto_profil VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Tidak perlu revert ke ukuran kecil - itu memang salah.
    }
};
