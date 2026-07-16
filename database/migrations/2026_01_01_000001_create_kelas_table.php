<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Catatan: kolom ditebak dari pola nama_kelas/walikelas di kode lama (bersihkelas.php, denahkelas.php).
    // Sesuaikan setelah struktur.sql asli tersedia.
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 20)->unique(); // contoh: 7A, 8B, 9C
            $table->unsignedTinyInteger('tingkat')->nullable(); // 7, 8, 9
            $table->foreignId('wali_kelas_id')->nullable()->constrained('guru')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
