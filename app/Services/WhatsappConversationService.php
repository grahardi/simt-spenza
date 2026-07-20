<?php

namespace App\Services;

use App\Models\AbsenSiswa;
use App\Models\AjuanWhatsapp;
use App\Models\Guru;
use App\Models\KodeGuru;
use App\Models\Member;
use App\Models\Siswa;
use App\Models\SiswaWhatsapp;
use App\Models\WhatsappMenu;
use App\Models\WhatsappSesi;
use App\Models\WhatsappTemplate;
use Illuminate\Support\Facades\Storage;

/**
 * "Otak" alur percakapan bot WhatsApp (registrasi siswa, registrasi guru,
 * absen, jadwal) - dipakai BERSAMA oleh WhatsappWebhookController (Baileys)
 * dan WhatsappMetaWebhookController (Cloud API resmi Meta), supaya logikanya
 * tidak dobel di 2 tempat. Controller cuma urus: parsing payload platform
 * masing-masing -> panggil balas() di sini -> kirim balasannya (via response
 * JSON untuk Baileys, atau via WhatsappMetaService::kirimPesan untuk Meta).
 */
class WhatsappConversationService
{
    public function balas(string $nomor, string $teks, ?string $gambarBase64 = null): string
    {
        $sesi = WhatsappSesi::firstOrCreate(['nomor' => $nomor], ['langkah' => 'menu']);
        $teks = trim($teks);

        return match ($sesi->langkah) {
            'registrasi_input_induk' => $this->prosesRegistrasiInputInduk($sesi, $teks),
            'registrasi_konfirmasi' => $this->prosesRegistrasiKonfirmasi($sesi, $teks),
            'registrasi_guru_input_kode' => $this->prosesRegistrasiGuruInputKode($sesi, $teks),
            'registrasi_guru_konfirmasi' => $this->prosesRegistrasiGuruKonfirmasi($sesi, $teks),
            'pilih_siswa' => $this->prosesPilihSiswa($sesi, $teks),
            'pilih_jenis' => $this->prosesPilihJenis($sesi, $teks),
            'tunggu_selfie' => $this->prosesTungguSelfie($sesi, $teks, $gambarBase64),
            'tunggu_surat' => $this->prosesTungguSurat($sesi, $teks, $gambarBase64),
            default => $this->prosesMenuUtama($sesi, $nomor, $teks),
        };
    }

    private function teksMenu(): string
    {
        $daftar = WhatsappMenu::where('aktif', true)->orderBy('urutan')->get();
        $baris = $daftar->map(fn ($m) => "*{$m->kode}* - {$m->label}")->implode("\n");

        return WhatsappTemplate::get('menu_utama', ['daftar_menu' => $baris]);
    }

    private function prosesMenuUtama(WhatsappSesi $sesi, string $nomor, string $teks): string
    {
        $pilihan = strtolower(trim($teks));

        if ($pilihan === 'regis-guru') {
            $sesi->update(['langkah' => 'registrasi_guru_input_kode']);

            return WhatsappTemplate::get('registrasi_guru_prompt');
        }

        if ($pilihan === 'jadwal' || str_starts_with($pilihan, 'jadwal ')) {
            $namaHari = trim(substr($pilihan, 6)); // kosong = hari ini, atau nama hari (senin/selasa/dst)

            return $this->jadwalMengajar($nomor, $namaHari);
        }

        $item = WhatsappMenu::where('aktif', true)
            ->whereRaw('LOWER(kode) = ?', [$pilihan])
            ->first();

        if (!$item) {
            return $this->teksMenu();
        }

        if ($item->kode === 'registrasi') {
            $sudahTerdaftar = $this->infoSudahTerdaftar($nomor);

            if ($sudahTerdaftar) {
                // Sudah terdaftar - tidak perlu lanjut minta Nomor Induk lagi,
                // cukup arahkan ke perintah yang relevan. Sesi TETAP di 'menu'.
                return $sudahTerdaftar."\n\nSilakan ketik *absen* atau *info* saja.";
            }

            $sesi->update(['langkah' => 'registrasi_input_induk']);

            return WhatsappTemplate::get('registrasi_prompt');
        }

        if ($item->kode === 'absen') {
            return $this->mulaiAbsen($sesi, $nomor);
        }

        return $item->balasan ?? $this->teksMenu();
    }

    /**
     * $namaHari kosong = jadwal hari ini. Kalau diisi (misal "senin"),
     * dicocokkan ke SENIN/SELASA/dst di database - huruf besar/kecil dan
     * spasi ekstra diabaikan, jadi "Senin", "senin", " SENIN " semua cocok.
     */
    private function jadwalMengajar(string $nomor, string $namaHari = ''): string
    {
        $sepuluhDigit = substr($nomor, -10);
        $guru = Guru::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->first();

        if (!$guru) {
            return WhatsappTemplate::get('jadwal_guru_belum_registrasi');
        }

        $hariValid = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT', 'SABTU', 'MINGGU'];

        if ($namaHari === '') {
            $hari = Member::namaHariJakartaHuruBesar();
        } else {
            $hari = strtoupper(trim($namaHari));
            if (!in_array($hari, $hariValid, true)) {
                return "Nama hari *\"{$namaHari}\"* tidak dikenali. Ketik salah satu: jadwal senin, jadwal selasa, jadwal rabu, jadwal kamis, atau jadwal jumat (atau *jadwal* saja untuk hari ini).";
            }
        }

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

    /**
     * Kalau nomor ini sudah terhubung ke 1/lebih siswa, kasih tahu di awal
     * proses registrasi - supaya wali tidak bingung/kaget kalau nanti ternyata
     * "sudah terdaftar" pas di tengah proses (nomor tetap boleh dipakai lagi
     * buat tambah anak lain, ini cuma info, bukan penghalang).
     */
    private function infoSudahTerdaftar(string $nomor): ?string
    {
        $sepuluhDigit = substr($nomor, -10);
        $daftarSiswa = Siswa::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->get();

        if ($daftarSiswa->isEmpty()) {
            return null;
        }

        $daftarNama = $daftarSiswa->map(fn ($s) => $s->nama_lengkap.' ('.$s->kelas.')')->implode(', ');

        return "\xE2\x84\xB9\xEF\xB8\x8F Nomor ini sudah terdaftar atas nama: *{$daftarNama}*.";
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
            $siswa = $daftarSiswa->first();

            $cekTerabsen = $this->cekSudahTerabsen($siswa);
            if ($cekTerabsen) {
                return $cekTerabsen;
            }

            $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $siswa->id_member]);

            return WhatsappTemplate::get('absen_pilih_jenis', [
                'nama' => $siswa->nama_lengkap,
                'kelas' => $siswa->kelas,
            ]);
        }

        $sesi->update(['langkah' => 'pilih_siswa']);
        $daftar = $daftarSiswa->values()->map(fn ($s, $i) => ($i + 1).'. '.$s->nama_lengkap.' ('.$s->kelas.')')->implode("\n");

        return WhatsappTemplate::get('absen_pilih_siswa', ['daftar' => $daftar]);
    }

    /** Kalau siswa sudah tercatat absen resmi hari ini, jangan lanjut - kasih tahu statusnya. */
    private function cekSudahTerabsen(Siswa $siswa): ?string
    {
        $absenHariIni = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->whereDate('tgl_absen', now())
            ->first();

        if (!$absenHariIni) {
            return null;
        }

        // Alfa boleh dikoreksi lewat ajuan WA susulan (misal ternyata sakit,
        // baru dikabari belakangan) - begitu di-ACC nanti otomatis menimpa
        // status Alfa jadi Sakit/Ijin. Yang benar-benar dikunci cuma kalau
        // sudah ada keterangan resmi (Sakit/Ijin/Dispensasi) - itu baru tidak
        // perlu diajukan ulang.
        if ($absenHariIni->keterangan === 'a') {
            return null;
        }

        return WhatsappTemplate::get('absen_sudah_terabsen', [
            'nama' => $siswa->nama_lengkap,
            'status' => strtolower($absenHariIni->labelKeterangan()),
        ]);
    }

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

    private function prosesRegistrasiKonfirmasi(WhatsappSesi $sesi, string $teks): string
    {
        $jawaban = strtolower(trim($teks));

        if ($jawaban === 'batal' || str_contains($jawaban, 'tidak')) {
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_dibatalkan')."\n\n".$this->teksMenu();
        }

        $label = match (true) {
            $jawaban === '1' || str_contains($jawaban, 'ayah') => 'Ayah',
            $jawaban === '2' || str_contains($jawaban, 'ibu') => 'Ibu',
            $jawaban === '3' || str_contains($jawaban, 'wali') || str_contains($jawaban, 'lainnya') => 'Wali',
            default => null,
        };

        if (!$label) {
            return WhatsappTemplate::get('registrasi_konfirmasi_invalid');
        }

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

        if ($jumlahSaatIni >= SiswaWhatsapp::MAKSIMAL_PER_SISWA) {
            $sesi->reset();

            return WhatsappTemplate::get('registrasi_maksimal', [
                'nama' => $siswa->nama_lengkap,
                'maksimal' => SiswaWhatsapp::MAKSIMAL_PER_SISWA,
            ]);
        }

        $siswa->nomorWhatsapp()->create(['nomor' => $sesi->nomor, 'label' => $label]);
        $sesi->reset();

        return WhatsappTemplate::get('registrasi_berhasil', ['nama' => $siswa->nama_lengkap]);
    }

    private function prosesPilihSiswa(WhatsappSesi $sesi, string $teks): string
    {
        if (strtolower(trim($teks)) === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('batal_umum')."\n\n".$this->teksMenu();
        }

        $sepuluhDigit = substr($sesi->nomor, -10);
        $daftarSiswa = Siswa::whereHas('nomorWhatsapp', function ($q) use ($sepuluhDigit) {
            $q->where('nomor', 'like', '%'.$sepuluhDigit);
        })->get()->values();

        $pilihan = (int) trim($teks) - 1;
        if (!isset($daftarSiswa[$pilihan])) {
            return WhatsappTemplate::get('pilih_siswa_invalid');
        }

        $siswa = $daftarSiswa[$pilihan];

        $cekTerabsen = $this->cekSudahTerabsen($siswa);
        if ($cekTerabsen) {
            $sesi->reset();

            return $cekTerabsen;
        }

        $sesi->update(['langkah' => 'pilih_jenis', 'id_siswa_dipilih' => $siswa->id_member]);

        return WhatsappTemplate::get('absen_pilih_jenis', ['nama' => $siswa->nama_lengkap, 'kelas' => $siswa->kelas]);
    }

    private function prosesPilihJenis(WhatsappSesi $sesi, string $teks): string
    {
        $teks = trim($teks);

        if (strtolower($teks) === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('batal_umum')."\n\n".$this->teksMenu();
        }

        $jenis = match (true) {
            $teks === '1' || str_contains(strtolower($teks), 'sakit') => 's',
            $teks === '2' || str_contains(strtolower($teks), 'ijin') || str_contains(strtolower($teks), 'izin') => 'i',
            default => null,
        };

        if (!$jenis) {
            return WhatsappTemplate::get('pilih_jenis_invalid');
        }

        $sesi->update(['langkah' => 'tunggu_surat', 'jenis_dipilih' => $jenis]);
        $labelJenis = $jenis === 's' ? 'Sakit' : 'Ijin';

        return WhatsappTemplate::get('minta_selfie', ['jenis' => $labelJenis]);
    }

    /** Langkah 1/2 (baru): foto SURAT dulu. */
    private function prosesTungguSurat(WhatsappSesi $sesi, string $teks, ?string $gambarBase64): string
    {
        if (strtolower(trim($teks)) === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('batal_umum')."\n\n".$this->teksMenu();
        }

        if (!$gambarBase64) {
            return WhatsappTemplate::get('surat_invalid');
        }

        try {
            $binary = base64_decode($gambarBase64);
            $namaFile = 'surat-'.$sesi->id_siswa_dipilih.'-'.now()->format('Ymd-His').'.jpg';
            Storage::disk('public')->put('ajuan-whatsapp/'.$namaFile, $binary);

            $sesi->update(['langkah' => 'tunggu_selfie', 'foto_sementara' => 'ajuan-whatsapp/'.$namaFile]);

            return WhatsappTemplate::get('selfie_diterima_minta_surat');
        } catch (\Throwable $e) {
            return "Maaf, terjadi kendala menyimpan foto surat. Silakan kirim ulang.";
        }
    }

    /**
     * Langkah 2/2 (baru): foto SELFIE Bapak/Ibu Wali sambil memegang surat
     * tadi (BUKAN foto Ananda) - dipakai buat verifikasi identitas pelapor.
     */
    private function prosesTungguSelfie(WhatsappSesi $sesi, string $teks, ?string $gambarBase64): string
    {
        if (strtolower(trim($teks)) === 'batal') {
            $sesi->reset();

            return WhatsappTemplate::get('batal_umum')."\n\n".$this->teksMenu();
        }

        if (!$gambarBase64) {
            return WhatsappTemplate::get('selfie_invalid');
        }

        try {
            $binary = base64_decode($gambarBase64);
            $namaFile = 'selfie-'.$sesi->id_siswa_dipilih.'-'.now()->format('Ymd-His').'.jpg';
            Storage::disk('public')->put('ajuan-whatsapp/'.$namaFile, $binary);

            AjuanWhatsapp::create([
                'nomor_wa' => $sesi->nomor,
                'id_siswa' => $sesi->id_siswa_dipilih,
                'jenis' => $sesi->jenis_dipilih,
                'foto_surat' => $sesi->foto_sementara,
                'foto_selfie' => 'ajuan-whatsapp/'.$namaFile,
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
