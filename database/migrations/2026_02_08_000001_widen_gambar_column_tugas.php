<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kolom peninggalan sistem lama, terlalu pendek buat path baru yang
        // lebih panjang (nama file + uniqid). Pola yang sama pernah ditemukan
        // di absen_siswa.gambar, lapor_absen.gambar/gambarwali, dst.
        DB::statement('ALTER TABLE tugas MODIFY gambar VARCHAR(255) NULL');
    }

    public function down(): void
    {
        // Tidak perlu revert
    }
};
