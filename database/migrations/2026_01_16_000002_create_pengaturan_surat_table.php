<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_surat', function (Blueprint $table) {
            $table->id();
            $table->string('kode_baku', 50)->default('-'); // contoh: 35.07.301.09.43
            $table->timestamps();
        });

        // 1 baris saja, dipakai sebagai pengaturan global
        DB::table('pengaturan_surat')->insert(['kode_baku' => '-', 'created_at' => now(), 'updated_at' => now()]);
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_surat');
    }
};
