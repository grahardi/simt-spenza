<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nama file surat sekarang lebih panjang (pakai kode status+nama+tanggal,
        // bukan cuma id+timestamp) - lebarkan kolomnya jaga-jaga sama seperti
        // perbaikan serupa di kolom gambar lainnya sebelumnya.
        if (Schema::hasColumn('ajuan_absensi', 'gambar')) {
            DB::statement('ALTER TABLE ajuan_absensi MODIFY gambar VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        // Tidak perlu revert
    }
};
