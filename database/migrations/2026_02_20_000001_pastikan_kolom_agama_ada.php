<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Aman dijalankan meski kolom 'agama' sudah ada dari sistem lama (cek dulu).
        if (!Schema::hasColumn('datasiswa', 'agama')) {
            Schema::table('datasiswa', function (Blueprint $table) {
                $table->string('agama')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('datasiswa', 'agama')) {
            Schema::table('datasiswa', function (Blueprint $table) {
                $table->dropColumn('agama');
            });
        }
    }
};
