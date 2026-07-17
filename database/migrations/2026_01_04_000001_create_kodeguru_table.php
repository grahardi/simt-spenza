<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabel penyimpan hasil pencocokan kode guru dari file Excel jadwal
     * (sheet "kodeguru") ke tabel `guru` asli di database. Kode di Excel
     * (02-51) TIDAK sama dengan id_guru asli di database - jadi perlu
     * dicocokkan dulu lewat nama (fuzzy match), hasilnya disimpan di sini
     * supaya bisa direview/dikoreksi manual sebelum dipakai mengisi
     * `datajadwal`, dan supaya sinkronisasi berikutnya tidak mencocokkan
     * ulang dari nol.
     */
    public function up(): void
    {
        Schema::create('kodeguru', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 10)->unique();
            $table->string('nama_excel');
            $table->string('mapel')->nullable();
            $table->foreignId('id_guru')->nullable()->constrained('guru', 'id_guru')->nullOnDelete();
            $table->unsignedTinyInteger('skor_kecocokan')->nullable()
                ->comment('0-100, persentase kemiripan nama hasil fuzzy match');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kodeguru');
    }
};
