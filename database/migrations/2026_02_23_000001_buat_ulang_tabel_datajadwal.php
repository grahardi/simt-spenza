<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel datajadwal ternyata TIDAK PERNAH dibuat lewat migration Laravel
     * (dulu bagian dari database lama/legacy) - jadi begitu MySQL crash dan
     * tabelnya hilang, tidak ada cara Laravel bikin ulang strukturnya sendiri.
     * Migration ini bikin ulang strukturnya dari nol berdasarkan kolom yang
     * dipakai model DataJadwal & command jadwal:sinkron - datanya sendiri
     * TETAP AMAN di database/data/jadwal_matrix.php & waktu_pelajaran.php,
     * tinggal dijalankan ulang setelah tabel ini ada.
     */
    public function up(): void
    {
        if (Schema::hasTable('datajadwal')) {
            return; // sudah ada (mis. migration ini dijalankan bukan pas darurat) - jangan timpa
        }

        Schema::create('datajadwal', function (Blueprint $table) {
            $table->unsignedInteger('id')->primary(); // manual, BUKAN auto-increment (lihat catatan di jadwal:sinkron)
            $table->string('hari', 20);
            $table->unsignedInteger('jamhari');
            $table->string('kelas', 20);
            $table->unsignedInteger('kodejam')->nullable();
            $table->unsignedInteger('kodeguru')->nullable();
            $table->string('mapel', 50)->nullable();
            $table->string('kodekelas', 20)->nullable();
            $table->string('waktu', 30)->nullable();

            $table->index(['hari', 'jamhari']);
            $table->index('kodeguru');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('datajadwal');
    }
};
