<?php

namespace App\Http\Controllers;

use App\Models\AjuanWhatsapp;
use App\Models\Guru;
use App\Models\GuruWhatsapp;
use App\Models\KodeGuru;
use App\Models\Member;
use App\Models\Siswa;
use App\Models\SiswaWhatsapp;
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
            'registrasi_guru_input_kode' => $this->prosesRegistrasiGuruInputKode($sesi, $teks),
            'registrasi_guru_konfirmasi' => $this->prosesRegistrasiGuruKonfirmasi($sesi, $teks),
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

        // Fitur tersembunyi guru - SENGAJA tidak masuk daftar whatsapp_menu
        // (tidak tampil di teks menu), cuma bisa dipakai kalau tahu kodenya.
        if ($pilihan === 'regis-guru') {
            $sesi->update(['langkah' => 'registrasi_guru_input_kode']);

            return WhatsappTemplate::get('registrasi_guru_prompt');
        }

        if ($pilihan === 'jadwal') {
            return $this->jadwalMengajarHariIni($nomor);
        }

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

    /**
     * Fitur tersembunyi - jadwal mengajar guru HARI INI (waktu Jakarta),
     * cuma jalan kalau nomor ini sudah terhubung ke data guru lewat 'regis-guru'.
     */
    private function jadwalMengajarHariIni(string $nomor): string
    {
        $sepuluhDigit = substr($nomor, -10);
        $guru = Guru::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->first();

        if (!$guru) {
            return WhatsappTemplate::get('jadwal_guru_belum_registrasi');
        }

        $hari = Member::namaHariJakartaHuruBesar();

        $jadwal = \App\Models\DataJadwal::where('kodeguru', $guru->id_guru)
            ->where('hari', $hari)
            ->orderBy('jamhari')
            ->get();

        if ($jadwal->isEmpty()) {
            return WhatsappTemplate::get('jadwal_guru_kosong', ['hari' => $hari]);
        }

        $baris = $jadwal->map(function ($j) {
            $waktu = $j->waktu ? $j->waktu.' - ' : '';
            return "{$waktu}{$j->kelas}: {$j->mapelLengkap()}";
        })->implode("\n");

        return "*Jadwal Mengajar {$guru->nama} - {$hari}*\n\n{$baris}";
    }

    /** Registrasi guru langkah 1: input Kode Guru, cari lewat tabel kodeguru. */
    private function prosesRegistrasiGuruInputKode(WhatsappSesi $sesi, string $teks): string
    {
        if (strtolower(trim($teks)) === 'batal') {
            $sesi->reset();

            return $this->teksMenu();
        }

        $kode = preg_replace('/\D/', '', $teks);
        $refGuru = $kode !== '' ? KodeGuru::where('kode', $kode)->whereNotNull('id_guru')->first() : null;

        if (!$refGuru) {
            return WhatsappTemplate::get('registrasi_guru_tidak_ditemukan', ['kode' => $teks]);
        }

        $sesi->update(['langkah' => 'registrasi_guru_konfirmasi', 'id_guru_calon_registrasi' => $refGuru->id_guru]);

        return WhatsappTemplate::get('registrasi_guru_konfirmasi', [
            'nama' => $refGuru->guru->nama ?? $refGuru->nama_excel,
            'mapel' => $refGuru->mapel ?? '-',
        ]);
    }

    /** Registrasi guru langkah 2: konfirmasi, baru simpan ke guru_whatsapp. */
    private function prosesRegistrasiGuruKonfirmasi(WhatsappSesi $sesi, string $teks): string
    {
        $jawaban = strtolower(trim($teks));

        if (str_contains($jawaban, 'ya')) {
            $guru = Guru::find($sesi->id_guru_calon_registrasi);

            if (!$guru) {
                $sesi->reset();

                return $this->teksMenu();
            }

            if (!$guru->nomorWhatsapp()->where('nomor', $sesi->nomor)->exists()) {
                $guru->nomorWhatsapp()->create(['nomor' => $sesi->nomor]);
            }

            $sesi->reset();

            return WhatsappTemplate::get('registrasi_guru_berhasil', ['nama' => $guru->nama]);
        }

        if (str_contains($jawaban, 'tidak') || $jawaban === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_dibatalkan')."\n\n".$this->teksMenu();
        }

        return WhatsappTemplate::get('registrasi_konfirmasi_invalid');
    }

    private function mulaiAbsen(WhatsappSesi $sesi, string $nomor): string
    {
        $sepuluhDigit = substr($nomor, -10);
        $daftarSiswa = Siswa::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->get();

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

    /** Registrasi langkah 2: konfirmasi, baru benar-benar simpan ke data siswa (maks 3 nomor per siswa). */
    private function prosesRegistrasiKonfirmasi(WhatsappSesi $sesi, string $teks): string
    {
        $jawaban = strtolower(trim($teks));

        if (str_contains($jawaban, 'ya')) {
            $siswa = Siswa::find($sesi->id_siswa_calon_registrasi);

            if (!$siswa) {
                $sesi->reset();

                return $this->teksMenu();
            }

            $sudahAda = $siswa->nomorWhatsapp()->where('nomor', $sesi->nomor)->exists();

            if ($sudahAda) {
                $sesi->reset();

                return WhatsappTemplate::get('registrasi_sudah_ada', ['nama' => $siswa->nama_lengkap]);
            }

            $jumlahSaatIni = $siswa->nomorWhatsapp()->count();

            if ($jumlahSaatIni >= \App\Models\SiswaWhatsapp::MAKSIMAL_PER_SISWA) {
                $sesi->reset();

                return WhatsappTemplate::get('registrasi_maksimal', [
                    'nama' => $siswa->nama_lengkap,
                    'maksimal' => \App\Models\SiswaWhatsapp::MAKSIMAL_PER_SISWA,
                ]);
            }

            $siswa->nomorWhatsapp()->create(['nomor' => $sesi->nomor]);
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_berhasil', ['nama' => $siswa->nama_lengkap]);
        }

        if (str_contains($jawaban, 'tidak') || $jawaban === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_dibatalkan')."\n\n".$this->teksMenu();
        }

        return WhatsappTemplate::get('registrasi_konfirmasi_invalid');
    }

    private function prosesPilihSiswa(WhatsappSesi $sesi, string $teks): string
    {
        $sepuluhDigit = substr($sesi->nomor, -10);
        $daftarSiswa = Siswa::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->get()->values();

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
