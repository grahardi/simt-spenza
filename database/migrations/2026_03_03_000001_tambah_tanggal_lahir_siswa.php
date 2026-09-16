<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('datasiswa', 'tanggal_lahir')) {
            Schema::table('datasiswa', function ($table) {
                $table->date('tanggal_lahir')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('datasiswa', 'tanggal_lahir')) {
            Schema::table('datasiswa', function ($table) {
                $table->dropColumn('tanggal_lahir');
            });
        }
    }
};
