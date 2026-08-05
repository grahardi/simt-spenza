<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('agenda_berkas_lainnya', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_agenda');
            $table->string('nama_file');
            $table->string('path');
            $table->timestamps();

            $table->foreign('id_agenda')->references('id')->on('agenda')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agenda_berkas_lainnya');
    }
};
