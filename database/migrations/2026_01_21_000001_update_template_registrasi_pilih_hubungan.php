<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('whatsapp_template')->where('kode', 'registrasi_konfirmasi')->update([
            'keterangan' => 'Registrasi: konfirmasi siswa ditemukan + pilih status hubungan. Placeholder: {nama}, {kelas}',
            'teks' => "Ditemukan: *{nama}* ({kelas})\n\nApakah benar ini anak Bapak/Ibu? Pilih status hubungan dengan ananda:\n1. Ayah\n2. Ibu\n3. Wali/Lainnya\n\n(ketik *batal* untuk membatalkan)",
        ]);

        DB::table('whatsapp_template')->where('kode', 'registrasi_konfirmasi_invalid')->update([
            'teks' => "Mohon balas *1* (Ayah), *2* (Ibu), atau *3* (Wali/Lainnya) - atau ketik *batal*.",
        ]);
    }

    public function down(): void
    {
        // Tidak perlu revert - biar tidak menimpa perubahan manual admin lewat panel.
    }
};
