<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kode template TETAP sama (supaya histori/pengaturan Superadmin tidak
        // rusak), tapi ISI dan URUTAN pemakaiannya ditukar sesuai arahan:
        // surat keterangan dulu (langkah 1), baru selfie WALI (bukan anak)
        // sambil pegang surat itu (langkah 2), buat verifikasi identitas.
        DB::table('whatsapp_template')->where('kode', 'minta_selfie')->update([
            'keterangan' => 'Absen: minta foto SURAT keterangan (langkah 1/2, setelah pilih jenis). Placeholder: {jenis}',
            'teks' => "Baik, diajukan *{jenis}*.\n\nLangkah 1/2: kirim *foto surat keterangan* (surat dokter/surat orang tua) sekarang.\n\n(ketik *batal* untuk keluar)",
        ]);

        DB::table('whatsapp_template')->where('kode', 'selfie_diterima_minta_surat')->update([
            'keterangan' => 'Absen: surat diterima, minta foto selfie WALI pegang surat (langkah 2/2)',
            'teks' => "Foto surat diterima \xE2\x9C\x85\n\nLangkah 2/2: kirim *foto selfie Bapak/Ibu Wali* sambil memegang surat tadi (BUKAN foto Ananda) - untuk verifikasi.\n\n(ketik *batal* untuk keluar)",
        ]);
    }

    public function down(): void
    {
        // Tidak perlu revert isi teks - biar tidak menimpa perubahan manual admin lewat panel.
    }
};
