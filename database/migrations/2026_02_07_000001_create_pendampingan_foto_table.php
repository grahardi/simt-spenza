<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendampingan_foto', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_pendampingan');
            $table->string('path');
            $table->timestamps();

            $table->foreign('id_pendampingan')->references('id')->on('pendampingan_wali')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendampingan_foto');
    }
};
