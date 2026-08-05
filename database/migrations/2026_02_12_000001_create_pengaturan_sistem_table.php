<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_sistem', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_wa_kepsek')->nullable();
            $table->timestamps();
        });

        // Baris tunggal (singleton pattern, sama seperti pengaturan_surat)
        \Illuminate\Support\Facades\DB::table('pengaturan_sistem')->insert([
            'nomor_wa_kepsek' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_sistem');
    }
};
