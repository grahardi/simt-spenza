<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Password lama disimpan plain text di kolom varchar(20) - terlalu pendek
     * untuk hash bcrypt (60 karakter). Diperlebar supaya password bisa
     * di-hash ulang secara bertahap (lihat LegacyPasswordEloquentUserProvider)
     * tanpa memaksa reset password semua akun sekaligus.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('password', 255)->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->string('password', 20)->nullable()->change();
        });
    }
};
