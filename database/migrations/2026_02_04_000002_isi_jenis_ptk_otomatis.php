<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Isi otomatis berdasarkan data yang sudah ada: yang terhubung ke
        // id_guru berarti guru, yang terhubung ke id_karyawan berarti tenaga
        // administrasi. Yang tidak terhubung ke keduanya (misal superadmin
        // murni) dibiarkan kosong - bisa diisi manual kalau perlu.
        DB::table('member')->whereNotNull('id_guru')->update(['jenis_ptk' => 'guru']);
        DB::table('member')->whereNull('id_guru')->whereNotNull('id_karyawan')->update(['jenis_ptk' => 'tenaga_administrasi']);
    }

    public function down(): void
    {
        // Tidak perlu revert - cuma mengisi data, tidak mengubah struktur.
    }
};
