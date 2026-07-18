<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('siswa_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_siswa');
            $table->string('nomor', 20);
            $table->string('label', 20)->nullable(); // Ayah/Ibu/Wali, opsional
            $table->timestamps();

            $table->foreign('id_siswa')->references('id_member')->on('datasiswa')->cascadeOnDelete();
            $table->unique(['id_siswa', 'nomor']); // 1 nomor sama tidak dobel untuk siswa yang sama
        });

        // Pindahkan nomor yang sudah terlanjur terdaftar di kolom lama
        // (datasiswa.whatsapp) supaya tidak hilang begitu kolom itu tidak
        // dipakai lagi secara aktif oleh sistem.
        $existing = DB::table('datasiswa')
            ->whereNotNull('whatsapp')
            ->where('whatsapp', '!=', '')
            ->get(['id_member', 'whatsapp']);

        foreach ($existing as $row) {
            DB::table('siswa_whatsapp')->insertOrIgnore([
                'id_siswa' => $row->id_member,
                'nomor' => preg_replace('/\D/', '', $row->whatsapp),
                'label' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('siswa_whatsapp');
    }
};
