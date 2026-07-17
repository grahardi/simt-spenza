<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Karena situs sekarang bisa diakses publik, semua akun yang passwordnya
     * masih plain text atau belum pernah login sama sekali WAJIB ganti
     * password dulu sebelum bisa pakai sistem. Default true untuk SEMUA
     * akun yang sudah ada - aman karena belum tentu semuanya sudah login
     * lewat sistem baru ini.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->boolean('wajib_ganti_password')->default(true)->after('password');
            $table->timestamp('last_login_at')->nullable()->after('wajib_ganti_password');
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn(['wajib_ganti_password', 'last_login_at']);
        });
    }
};
