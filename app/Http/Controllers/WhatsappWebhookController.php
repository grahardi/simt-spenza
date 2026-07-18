<?php

namespace App\Http\Controllers;

use App\Models\AjuanWhatsapp;
use App\Models\Siswa;
use App\Models\WhatsappMenu;
use App\Models\WhatsappSesi;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhatsappWebhookController extends Controller
{
    /**
     * Endpoint yang dipanggil bot Node.js tiap ada pesan WA masuk.
     * Balasannya dikirim lewat field 'balasan' di response JSON, bot yang
     * meneruskan ke WhatsApp - Laravel tidak pernah kirim langsung ke bot
     * kecuali lewat WhatsappBotService (dipakai pas notif "sudah di-ACC").
     *
     * SEMUA teks balasan diambil lewat WhatsappTemplate::get() dari tabel
     * whatsapp_template - bisa diedit di Superadmin > Template Balasan Bot,
     * tanpa perlu ubah kode. Menu utama (registrasi/absen/info dkk) diambil
     * dari whatsapp_menu, lihat Superadmin > Menu Bot WhatsApp.
     */
    public function masuk(Request $request)
    {
        if ($request->header('X-Bot-Secret') !== config('whatsapp.secret')) {
            abort(401, 'Token tidak valid');
        }

        $nomor = preg_replace('/\D/', '', (string) $request->input('nomor'));
        $teks = trim((string) $request->input('teks'));
        $gambarBase64 = $request->input('gambar_base64');

        $sesi = WhatsappSesi::firstOrCreate(['nomor' => $nomor], ['langkah' => 'menu']);

        $balasan = match ($sesi->langkah) {
            'registrasi_input_induk' => $this->prosesRegistrasiInputInduk($sesi, $teks),
            'registrasi_konfirmasi' => $this->prosesRegistrasiKonfirmasi($sesi, $teks),
            'pilih_siswa' => $this->prosesPilihSiswa($sesi, $teks),
            'pilih_jenis' => $this->prosesPilihJenis($sesi, $teks),
            'tunggu_selfie' => $this->prosesTungguSelfie($sesi, $gambarBase64),
            'tunggu_surat' => $this->prosesTungguSurat($sesi, $gambarBase64),
            default => $this->prosesMenuUtama($sesi, $nomor, $teks),
        };

        return response()->json(['balasan' => $balasan]);
    }

    private function teksMenu(): string
    {
        $daftar = WhatsappMenu::where('aktif', true)->orderBy('urutan')->get();
        $baris = $daftar->map(fn ($m) => "*{$m->kode}* - {$m->label}")->implode("\n");

        return WhatsappTemplate::get('menu_utama', ['daftar_menu' => $baris]);
    }

    /**
     * Menu utama - hanya bereaksi kalau teksnya PERSIS sama dengan salah satu
     * kode menu yang aktif (case-insensitive), sesuai arahan. Kode 'registrasi'
     * dan 'absen' menjalankan alur terprogram, kode lain (tipe 'info') balas
     * teks statis yang sudah diatur superadmin lewat Menu Bot WhatsApp.
     */
    private function prosesMenuUtama(WhatsappSesi $sesi, string $nomor, string $teks): string
    {
        $pilihan = strtolower(trim($teks));

        $item = WhatsappMenu::where('aktif', true)
            ->whereRaw('LOWER(kode) = ?', [$pilihan])
            ->first();

        if (!$item) {
            return $this->teksMenu();
        }

        if ($item->kode === 'registrasi') {
            $sesi->update(['langkah' => 'registrasi_input_induk']);

            return WhatsappTemplate::get('registrasi_prompt');
        }

        if ($item->kode === 'absen') {
            return $this->mulaiAbsen($sesi, $nomor);
        }

        // Tipe 'info' - balasan teks statis, diatur lewat Superadmin > Menu Bot WhatsApp
        return $item->balasan ?? $this->teksMenu();
    }

    private function mulaiAbsen(WhatsappSesi $sesi, string $nomor): string
    {
        $daftarSiswa = Siswa::where('whatsapp', 'like', '%'.substr($nomor, -10).'%')->get();

        if ($daftarSiswa->isEmpty()) {
            return WhatsappTemplate::get('absen_belum_terdaftar');
        }

        if ($daftarSiswa->count() === 1) {
            $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $daftarSiswa->first()->id_member]);

            return WhatsappTemplate::get('absen_pilih_jenis', [
                'nama' => $daftarSiswa->first()->nama_lengkap,
                'kelas' => $daftarSiswa->first()->kelas,
            ]);
        }

        $sesi->update(['langkah' => 'pilih_siswa']);
        $daftar = $daftarSiswa->values()->map(fn ($s, $i) => ($i + 1).'. '.$s->nama_lengkap.' ('.$s->kelas.')')->implode("\n");

        return WhatsappTemplate::get('absen_pilih_siswa', ['daftar' => $daftar]);
    }

    /**
     * Registrasi langkah 1: input Nomor Induk, cari siswanya, minta konfirmasi
     * sebelum benar-benar menghubungkan (supaya tidak salah pasang gara-gara
     * salah ketik nomor induk).
     */
    private function prosesRegistrasiInputInduk(WhatsappSesi $sesi, string $teks): string
    {
        if (strtolower(trim($teks)) === 'batal') {
            $sesi->reset();

            return $this->teksMenu();
        }

        $noInduk = preg_replace('/\D/', '', $teks);
        $siswa = $noInduk !== '' ? Siswa::find($noInduk) : null;

        if (!$siswa) {
            return WhatsappTemplate::get('registrasi_tidak_ditemukan', ['induk' => $teks]);
        }

        $sesi->update(['langkah' => 'registrasi_konfirmasi', 'id_siswa_calon_registrasi' => $siswa->id_member]);

        return WhatsappTemplate::get('registrasi_konfirmasi', ['nama' => $siswa->nama_lengkap, 'kelas' => $siswa->kelas]);
    }

    /** Registrasi langkah 2: konfirmasi, baru benar-benar simpan ke data siswa. */
    private function prosesRegistrasiKonfirmasi(WhatsappSesi $sesi, string $teks): string
    {
        $jawaban = strtolower(trim($teks));

        if (str_contains($jawaban, 'ya')) {
            $siswa = Siswa::find($sesi->id_siswa_calon_registrasi);
            $siswa?->update(['whatsapp' => $sesi->nomor]);
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_berhasil', ['nama' => $siswa?->nama_lengkap]);
        }

        if (str_contains($jawaban, 'tidak') || $jawaban === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_dibatalkan')."\n\n".$this->teksMenu();
        }

        return WhatsappTemplate::get('registrasi_konfirmasi_invalid');
    }

    private function prosesPilihSiswa(WhatsappSesi $sesi, string $teks): string
    {
        $nomor = $sesi->nomor;
        $daftarSiswa = Siswa::where('whatsapp', 'like', '%'.substr($nomor, -10).'%')->get()->values();

        $pilihan = (int) trim($teks) - 1;
        if (!isset($daftarSiswa[$pilihan])) {
            return WhatsappTemplate::get('pilih_siswa_invalid');
        }

        $siswa = $daftarSiswa[$pilihan];
        $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $siswa->id_member]);

        return WhatsappTemplate::get('absen_pilih_jenis', ['nama' => $siswa->nama_lengkap, 'kelas' => $siswa->kelas]);
    }

    private function prosesPilihJenis(WhatsappSesi $sesi, string $teks): string
    {
        $teks = trim($teks);
        $jenis = match (true) {
            $teks === '1' || str_contains(strtolower($teks), 'sakit') => 's',
            $teks === '2' || str_contains(strtolower($teks), 'ijin') || str_contains(strtolower($teks), 'izin') => 'i',
            default => null,
        };

        if (!$jenis) {
            return WhatsappTemplate::get('pilih_jenis_invalid');
        }

        $sesi->update(['langkah' => 'tunggu_selfie', 'jenis_dipilih' => $jenis]);
        $labelJenis = $jenis === 's' ? 'Sakit' : 'Ijin';

        return WhatsappTemplate::get('minta_selfie', ['jenis' => $labelJenis]);
    }

    /** Langkah 1/2: minta & simpan foto selfie dulu, baru lanjut minta foto surat. */
    private function prosesTungguSelfie(WhatsappSesi $sesi, ?string $gambarBase64): string
    {
        if (!$gambarBase64) {
            return WhatsappTemplate::get('selfie_invalid');
        }

        try {
            $binary = base64_decode($gambarBase64);
            $namaFile = 'selfie-'.$sesi->id_siswa_dipilih.'-'.now()->format('Ymd-His').'.jpg';
            Storage::disk('public')->put('ajuan-whatsapp/'.$namaFile, $binary);

            $sesi->update(['langkah' => 'tunggu_surat', 'foto_sementara' => 'ajuan-whatsapp/'.$namaFile]);

            return WhatsappTemplate::get('selfie_diterima_minta_surat');
        } catch (\Throwable $e) {
            return "Maaf, terjadi kendala menyimpan foto selfie. Silakan kirim ulang.";
        }
    }

    /** Langkah 2/2: foto surat, baru setelah ini ajuan benar-benar disimpan (selfie + surat lengkap). */
    private function prosesTungguSurat(WhatsappSesi $sesi, ?string $gambarBase64): string
    {
        if (!$gambarBase64) {
            return WhatsappTemplate::get('surat_invalid');
        }

        try {
            $binary = base64_decode($gambarBase64);
            $namaFile = 'surat-'.$sesi->id_siswa_dipilih.'-'.now()->format('Ymd-His').'.jpg';
            Storage::disk('public')->put('ajuan-whatsapp/'.$namaFile, $binary);

            AjuanWhatsapp::create([
                'nomor_wa' => $sesi->nomor,
                'id_siswa' => $sesi->id_siswa_dipilih,
                'jenis' => $sesi->jenis_dipilih,
                'foto_surat' => 'ajuan-whatsapp/'.$namaFile,
                'foto_selfie' => $sesi->foto_sementara,
                'status' => 'menunggu',
                'created_at' => now(),
            ]);

            $siswa = Siswa::find($sesi->id_siswa_dipilih);
            $sesi->reset();

            return WhatsappTemplate::get('ajuan_berhasil', ['nama' => $siswa?->nama_lengkap]);
        } catch (\Throwable $e) {
            return "Maaf, terjadi kendala menyimpan foto surat. Silakan kirim ulang fotonya.";
        }
    }
}
