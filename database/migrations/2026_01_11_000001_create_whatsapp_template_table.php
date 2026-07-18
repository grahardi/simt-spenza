<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_template', function (Blueprint $table) {
            $table->id();
            $table->string('kode', 40)->unique(); // dipakai literal di kode, TIDAK bisa ditambah/dihapus lewat panel
            $table->string('keterangan'); // penjelasan kapan teks ini dipakai, buat superadmin
            $table->text('teks');
            $table->timestamps();
        });

        $default = [
            ['menu_utama', 'Salam pembuka + daftar menu. Placeholder: {daftar_menu}', "Assalamu'alaikum, Bapak/Ibu Wali Murid \xF0\x9F\x99\x8F\n\n*SIMT SMP Negeri 1 Turen*\n\nKetik salah satu:\n{daftar_menu}"],
            ['registrasi_prompt', 'Registrasi: minta Nomor Induk siswa', "Silakan ketik *Nomor Induk* siswa yang mau dihubungkan dengan nomor WhatsApp ini.\n\nKetik *batal* untuk kembali ke menu."],
            ['registrasi_tidak_ditemukan', 'Registrasi: Nomor Induk tidak ditemukan. Placeholder: {induk}', "Nomor Induk *{induk}* tidak ditemukan. Coba periksa lagi ya, atau ketik *batal* untuk kembali ke menu."],
            ['registrasi_konfirmasi', 'Registrasi: konfirmasi siswa ditemukan. Placeholder: {nama}, {kelas}', "Ditemukan: *{nama}* ({kelas})\n\nApakah benar ini anak Bapak/Ibu? Balas *YA* untuk menghubungkan nomor ini, atau *TIDAK* untuk batal."],
            ['registrasi_berhasil', 'Registrasi: berhasil terhubung. Placeholder: {nama}', "\xE2\x9C\x85 Berhasil! Nomor WhatsApp ini sekarang terhubung dengan *{nama}*.\n\nKetik *absen* kapan saja untuk mengajukan Sakit/Ijin."],
            ['registrasi_dibatalkan', 'Registrasi: dibatalkan user', "Registrasi dibatalkan."],
            ['registrasi_konfirmasi_invalid', 'Registrasi: balasan YA/TIDAK tidak dikenali', "Mohon balas *YA* atau *TIDAK* saja."],
            ['absen_belum_terdaftar', 'Absen: nomor belum terhubung ke siswa manapun', "Mohon maaf, nomor ini belum terhubung dengan data siswa manapun.\n\nKetik *registrasi* dulu untuk menghubungkan nomor ini dengan data anak Bapak/Ibu."],
            ['absen_pilih_jenis', 'Absen: siswa tunggal ditemukan, minta pilih Sakit/Ijin. Placeholder: {nama}, {kelas}', "Ananda *{nama}* ({kelas})\n\nAjukan apa hari ini?\nBalas *1* untuk Sakit\nBalas *2* untuk Ijin"],
            ['absen_pilih_siswa', 'Absen: beberapa siswa terhubung nomor sama. Placeholder: {daftar}', "Nomor ini terhubung dengan beberapa siswa:\n\n{daftar}\n\nBalas dengan angka pilihan Bapak/Ibu."],
            ['pilih_siswa_invalid', 'Absen: pilihan angka siswa tidak valid', "Pilihan tidak dikenali. Balas dengan angka sesuai daftar yang sudah dikirim."],
            ['pilih_jenis_invalid', 'Absen: pilihan Sakit/Ijin tidak valid', "Balas *1* untuk Sakit atau *2* untuk Ijin."],
            ['minta_selfie', 'Absen: minta foto selfie (langkah 1/2). Placeholder: {jenis}', "Baik, diajukan *{jenis}*.\n\nLangkah 1/2: kirim *foto selfie wajah* Ananda sekarang (untuk verifikasi)."],
            ['selfie_invalid', 'Absen: belum kirim foto selfie', "Mohon kirim *foto selfie* dulu ya (bukan teks)."],
            ['selfie_diterima_minta_surat', 'Absen: selfie diterima, minta foto surat (langkah 2/2)', "Foto selfie diterima \xE2\x9C\x85\n\nLangkah 2/2: kirim *foto surat keterangan* (surat dokter/surat orang tua) sekarang."],
            ['surat_invalid', 'Absen: belum kirim foto surat', "Mohon kirim *foto surat keterangan* ya (bukan teks)."],
            ['ajuan_berhasil', 'Absen: ajuan lengkap & tersimpan. Placeholder: {nama}', "\xE2\x9C\x85 Ajuan untuk *{nama}* berhasil diterima (selfie + surat lengkap).\n\nMenunggu diproses petugas piket. Kami akan kirim kabar begitu sudah diproses. Terima kasih \xF0\x9F\x99\x8F"],
        ];

        foreach ($default as [$kode, $keterangan, $teks]) {
            DB::table('whatsapp_template')->insert([
                'kode' => $kode,
                'keterangan' => $keterangan,
                'teks' => $teks,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_template');
    }
};
