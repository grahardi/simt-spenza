<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_log', function (Blueprint $table) {
            $table->id();
            $table->string('nomor', 20);
            $table->enum('arah', ['masuk', 'keluar']);
            $table->text('teks')->nullable();
            $table->string('sumber', 20)->default('meta'); // 'meta' atau 'baileys'
            $table->string('wamid', 100)->nullable(); // ID pesan asli dari Meta - cegah proses dobel kalau di-retry
            $table->timestamp('created_at')->useCurrent();

            $table->index(['nomor', 'created_at']);
            $table->unique('wamid');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_log');
    }
};
