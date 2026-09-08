<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_kartu_ujian', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->default('Kartu Peserta Sumatif Akhir Jenjang');
            $table->date('tanggal')->nullable();
            $table->timestamps();
        });

        DB::table('pengaturan_kartu_ujian')->insert([
            'judul' => 'Kartu Peserta Sumatif Akhir Jenjang',
            'tanggal' => now()->toDateString(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_kartu_ujian');
    }
};
