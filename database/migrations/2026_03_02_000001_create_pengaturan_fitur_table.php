<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengaturan_fitur', function (Blueprint $table) {
            $table->id();
            $table->string('role', 50);
            $table->string('fitur_key', 100); // slug dari label menu, contoh: "ajukan-bansos"
            $table->boolean('aktif')->default(true);
            $table->timestamps();

            $table->unique(['role', 'fitur_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pengaturan_fitur');
    }
};
