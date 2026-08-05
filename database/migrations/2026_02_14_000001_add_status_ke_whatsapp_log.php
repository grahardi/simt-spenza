<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('whatsapp_log', function (Blueprint $table) {
            $table->boolean('berhasil')->nullable()->after('sumber');
            $table->text('detail_error')->nullable()->after('berhasil');
        });
    }

    public function down(): void
    {
        Schema::table('whatsapp_log', function (Blueprint $table) {
            $table->dropColumn(['berhasil', 'detail_error']);
        });
    }
};
