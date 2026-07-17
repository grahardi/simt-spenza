<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Nyimpen "lagi di step mana" percakapan tiap nomor WA - supaya bot
        // tahu apakah user lagi milih siswa, milih sakit/ijin, atau nunggu foto.
        Schema::create('whatsapp_sesi', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 20)->unique();
            $table->string('langkah', 30)->default('menu'); // menu, pilih_siswa, pilih_jenis, tunggu_foto
            $table->unsignedInteger('id_siswa_dipilih')->nullable();
            $table->string('jenis_dipilih', 1)->nullable(); // s/i
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });

        // Ajuan yang masuk lewat WhatsApp - terpisah dari ajuan biasa (lapor_absen)
        // supaya piket bisa lihat mana yang datang dari WA vs dari admin absensi.
        Schema::create('ajuan_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_wa', 20);
            $table->unsignedInteger('id_siswa');
            $table->string('jenis', 1); // s = sakit, i = ijin
            $table->string('foto_surat');
            $table->string('status', 20)->default('menunggu'); // menunggu, disetujui, ditolak
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('diproses_at')->nullable();
            $table->unsignedInteger('diproses_oleh')->nullable(); // id_member piket yang ACC/tolak
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ajuan_whatsapp');
        Schema::dropIfExists('whatsapp_sesi');
    }
};
