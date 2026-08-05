<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('absensi_guru', function (Blueprint $table) {
            $table->string('foto')->nullable()->after('keterangan');
        });
    }

    public function down(): void
    {
        Schema::table('absensi_guru', function (Blueprint $table) {
            $table->dropColumn('foto');
        });
    }
};
