<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_log', function (Blueprint $table) {
            // 'berhasil' cuma nunjukin API-nya nerima permintaan. Kolom ini
            // buat status pengiriman SEBENARNYA (sent/delivered/read/failed)
            // yang dikirim Meta belakangan lewat webhook status terpisah.
            $table->string('status_pengiriman')->nullable()->after('detail_error');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_log', function (Blueprint $table) {
            $table->dropColumn('status_pengiriman');
        });
    }
};
