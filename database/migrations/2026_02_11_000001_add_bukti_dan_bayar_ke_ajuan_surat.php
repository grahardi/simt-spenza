<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ajuan_surat', function (Blueprint $table) {
            $table->string('foto_bukti_perjalanan')->nullable()->after('file_pdf');
            $table->enum('status_bayar', ['belum', 'sudah'])->default('belum')->after('foto_bukti_perjalanan');
            $table->decimal('nominal_transport', 12, 2)->nullable()->after('status_bayar');
            $table->integer('dibayar_oleh')->nullable()->after('nominal_transport');
            $table->timestamp('dibayar_at')->nullable()->after('dibayar_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('ajuan_surat', function (Blueprint $table) {
            $table->dropColumn(['foto_bukti_perjalanan', 'status_bayar', 'nominal_transport', 'dibayar_oleh', 'dibayar_at']);
        });
    }
};
