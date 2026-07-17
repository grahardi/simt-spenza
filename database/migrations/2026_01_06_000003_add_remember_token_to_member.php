<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Laravel butuh kolom `remember_token` di tabel user/member untuk fitur
     * "Ingat saya" (checkbox remember di form login) - belum ada di tabel
     * `member` asli karena itu tabel legacy, bukan bawaan Laravel.
     */
    public function up(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->rememberToken();
        });
    }

    public function down(): void
    {
        Schema::table('member', function (Blueprint $table) {
            $table->dropColumn('remember_token');
        });
    }
};
