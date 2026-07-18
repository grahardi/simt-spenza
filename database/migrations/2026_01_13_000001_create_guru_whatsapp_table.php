<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('guru_whatsapp', function (Blueprint $table) {
            $table->id();
            $table->integer('id_guru'); // signed, samakan tipe dengan guru.id_guru
            $table->string('nomor', 20);
            $table->timestamps();

            $table->foreign('id_guru')->references('id_guru')->on('guru')->cascadeOnDelete();
            $table->unique(['id_guru', 'nomor']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('guru_whatsapp');
    }
};
