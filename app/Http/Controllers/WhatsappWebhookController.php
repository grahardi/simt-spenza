<?php

namespace App\Http\Controllers;

use App\Models\AjuanWhatsapp;
use App\Models\Siswa;
use App\Models\WhatsappMenu;
use App\Models\WhatsappSesi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhatsappWebhookController extends Controller
{
    /**
     * Endpoint yang dipanggil bot Node.js tiap ada pesan WA masuk.
     * Balasannya dikirim lewat field 'balasan' di response JSON, bot yang
     * meneruskan ke WhatsApp - Laravel tidak pernah kirim langsung ke bot
     * kecuali lewat WhatsappBotService (dipakai pas notif "sudah di-ACC").
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
            'tunggu_foto' => $this->prosesTungguFoto($sesi, $gambarBase64),
            default => $this->prosesMenuUtama($sesi, $nomor, $teks),
        };

        return response()->json(['balasan' => $balasan]);
    }

    private function teksMenu(): string
    {
        $daftar = WhatsappMenu::where('aktif', true)->orderBy('urutan')->get();

        $baris = $daftar->map(fn ($m) => "*{$m->kode}* - {$m->label}")->implode("\n");

        return "Assalamu'alaikum, Bapak/Ibu Wali Murid \xF0\x9F\x99\x8F\n\n"
            ."*SIMT SMP Negeri 1 Turen*\n\n"
            ."Ketik salah satu:\n{$baris}";
    }

    /**
     * Menu utama - hanya bereaksi kalau teksnya PERSIS sama dengan salah satu
     * kode menu yang aktif (case-insensitive), sesuai arahan. Kode 'registrasi'
     * dan 'absen' menjalankan alur terprogram, kode lain (tipe 'info') balas
     * teks statis yang sudah diatur superadmin.
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

            return "Silakan ketik *Nomor Induk* siswa yang mau dihubungkan dengan nomor WhatsApp ini.\n\n"
                ."Ketik *batal* untuk kembali ke menu.";
        }

        if ($item->kode === 'absen') {
            return $this->mulaiAbsen($sesi, $nomor);
        }

        // Tipe 'info' - balasan teks statis, bebas diatur lewat Superadmin > Menu Bot WhatsApp
        return $item->balasan ?? $this->teksMenu();
    }

    private function mulaiAbsen(WhatsappSesi $sesi, string $nomor): string
    {
        $daftarSiswa = Siswa::where('whatsapp', 'like', '%'.substr($nomor, -10).'%')->get();

        if ($daftarSiswa->isEmpty()) {
            return "Mohon maaf, nomor ini belum terhubung dengan data siswa manapun.\n\n"
                ."Ketik *registrasi* dulu untuk menghubungkan nomor ini dengan data anak Bapak/Ibu.";
        }

        if ($daftarSiswa->count() === 1) {
            $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $daftarSiswa->first()->id_member]);

            return "Ananda *{$daftarSiswa->first()->nama_lengkap}* ({$daftarSiswa->first()->kelas})\n\n"
                ."Ajukan apa hari ini?\nBalas *1* untuk Sakit\nBalas *2* untuk Ijin";
        }

        $sesi->update(['langkah' => 'pilih_siswa']);
        $daftar = $daftarSiswa->values()->map(fn ($s, $i) => ($i + 1).'. '.$s->nama_lengkap.' ('.$s->kelas.')')->implode("\n");

        return "Nomor ini terhubung dengan beberapa siswa:\n\n{$daftar}\n\nBalas dengan angka pilihan Bapak/Ibu.";
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
            return "Nomor Induk *{$teks}* tidak ditemukan. Coba periksa lagi ya, atau ketik *batal* untuk kembali ke menu.";
        }

        $sesi->update(['langkah' => 'registrasi_konfirmasi', 'id_siswa_calon_registrasi' => $siswa->id_member]);

        return "Ditemukan: *{$siswa->nama_lengkap}* ({$siswa->kelas})\n\n"
            ."Apakah benar ini anak Bapak/Ibu? Balas *YA* untuk menghubungkan nomor ini, atau *TIDAK* untuk batal.";
    }

    /** Registrasi langkah 2: konfirmasi, baru benar-benar simpan ke data siswa. */
    private function prosesRegistrasiKonfirmasi(WhatsappSesi $sesi, string $teks): string
    {
        $jawaban = strtolower(trim($teks));

        if (str_contains($jawaban, 'ya')) {
            $siswa = Siswa::find($sesi->id_siswa_calon_registrasi);
            $siswa?->update(['whatsapp' => $sesi->nomor]);
            $sesi->reset();

            return "\xE2\x9C\x85 Berhasil! Nomor WhatsApp ini sekarang terhubung dengan *{$siswa?->nama_lengkap}*.\n\n"
                ."Ketik *absen* kapan saja untuk mengajukan Sakit/Ijin.";
        }

        if (str_contains($jawaban, 'tidak') || $jawaban === 'batal') {
            $sesi->reset();

            return "Registrasi dibatalkan.\n\n".$this->teksMenu();
        }

        return "Mohon balas *YA* atau *TIDAK* saja.";
    }

    private function prosesPilihSiswa(WhatsappSesi $sesi, string $teks): string
    {
        $nomor = $sesi->nomor;
        $daftarSiswa = Siswa::where('whatsapp', 'like', '%'.substr($nomor, -10).'%')->get()->values();

        $pilihan = (int) trim($teks) - 1;
        if (!isset($daftarSiswa[$pilihan])) {
            return "Pilihan tidak dikenali. Balas dengan angka sesuai daftar yang sudah dikirim.";
        }

        $siswa = $daftarSiswa[$pilihan];
        $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $siswa->id_member]);

        return "Ananda *{$siswa->nama_lengkap}* ({$siswa->kelas})\n\n"
            ."Ajukan apa hari ini?\nBalas *1* untuk Sakit\nBalas *2* untuk Ijin";
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
            return "Balas *1* untuk Sakit atau *2* untuk Ijin.";
        }

        $sesi->update(['langkah' => 'tunggu_foto', 'jenis_dipilih' => $jenis]);
        $labelJenis = $jenis === 's' ? 'Sakit' : 'Ijin';

        return "Baik, diajukan *{$labelJenis}*.\n\n"
            ."Silakan kirim *foto surat keterangan* (surat dokter/surat orang tua) sekarang.";
    }

    private function prosesTungguFoto(WhatsappSesi $sesi, ?string $gambarBase64): string
    {
        if (!$gambarBase64) {
            return "Mohon kirim *foto* surat keterangan ya, bukan teks.";
        }

        try {
            $binary = base64_decode($gambarBase64);
            $namaFile = 'wa-'.$sesi->id_siswa_dipilih.'-'.now()->format('Ymd-His').'.jpg';
            Storage::disk('public')->put('ajuan-whatsapp/'.$namaFile, $binary);

            AjuanWhatsapp::create([
                'nomor_wa' => $sesi->nomor,
                'id_siswa' => $sesi->id_siswa_dipilih,
                'jenis' => $sesi->jenis_dipilih,
                'foto_surat' => 'ajuan-whatsapp/'.$namaFile,
                'status' => 'menunggu',
                'created_at' => now(),
            ]);

            $siswa = Siswa::find($sesi->id_siswa_dipilih);
            $sesi->reset();

            return "\xE2\x9C\x85 Ajuan untuk *{$siswa?->nama_lengkap}* berhasil diterima.\n\n"
                ."Menunggu diproses petugas piket. Kami akan kirim kabar begitu sudah diproses. Terima kasih \xF0\x9F\x99\x8F";
        } catch (\Throwable $e) {
            return "Maaf, terjadi kendala menyimpan foto. Silakan kirim ulang fotonya.";
        }
    }
}
