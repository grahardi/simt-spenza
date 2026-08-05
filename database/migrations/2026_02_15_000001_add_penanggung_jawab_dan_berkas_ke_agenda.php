<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->integer('id_penanggung_jawab')->nullable()->after('kategori');
            $table->string('berkas_proposal')->nullable()->after('id_penanggung_jawab');
            $table->string('berkas_sk_kepanitiaan')->nullable()->after('berkas_proposal');
            $table->string('berkas_spj')->nullable()->after('berkas_sk_kepanitiaan');
        });
    }

    public function down(): void
    {
        Schema::table('agenda', function (Blueprint $table) {
            $table->dropColumn(['id_penanggung_jawab', 'berkas_proposal', 'berkas_sk_kepanitiaan', 'berkas_spj']);
        });
    }
};
