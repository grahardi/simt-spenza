<?php

namespace App\Http\Controllers;

use App\Models\AjuanSurat;
use App\Models\PengaturanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratTuguController extends Controller
{
    /** List semua ajuan surat (semua guru) - buat Tata Usaha proses. */
    public function index(Request $request)
    {
        $status = $request->input('status', 'menunggu');

        $daftar = AjuanSurat::with('guru')
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('ajuan-surat.tu-index', compact('daftar', 'status'));
    }

    /** Form Buat SPPD langsung dari Tata Usaha - beda dari ajuan guru, di sini TU pilih gurunya sendiri. */
    public function createSppd()
    {
        $daftarGuru = \App\Models\Guru::orderBy('nama')->get();

        return view('ajuan-surat.form-sppd', ['guru' => null, 'daftarGuru' => $daftarGuru, 'dariTu' => true]);
    }

    public function storeSppd(Request $request)
    {
        $request->validate(['id_guru' => ['required', 'integer', 'exists:guru,id_guru']]);

        $data = $request->validate([
            'isian_form' => ['required', 'string', 'max:300'],
            'tanggal_dasar' => ['nullable', 'date'],
            'nomor_surat_dasar' => ['nullable', 'string', 'max:100'],
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'jam_mulai' => ['required', 'string', 'max:10'],
            'jam_selesai' => ['nullable', 'string', 'max:10'],
            'tempat_tujuan' => ['required', 'string', 'max:200'],
            'tema' => ['required', 'string', 'max:200'],
            'berkas_pendukung' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'],
        ]);

        $data['hari'] = \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('l');
        $data['total_hari'] = !empty($data['tanggal_selesai'])
            ? \Carbon\Carbon::parse($data['tanggal'])->diffInDays(\Carbon\Carbon::parse($data['tanggal_selesai'])) + 1
            : 1;

        $filePendukung = null;
        if ($request->hasFile('berkas_pendukung')) {
            $filePendukung = $request->file('berkas_pendukung')->store('ajuan-surat/pendukung', 'public');
        }
        unset($data['berkas_pendukung']);

        $ajuan = AjuanSurat::create([
            'id_guru' => (int) $request->input('id_guru'),
            'jenis_surat' => 'sppd',
            'data' => $data,
            'file_pendukung' => $filePendukung,
            'status' => 'menunggu',
        ]);

        return redirect()->route('surat-tu.show', $ajuan)->with('status', 'SPPD berhasil dibuat, lanjutkan ke pemberian nomor surat.');
    }

    /** Detail lengkap 1 ajuan - sebelum dibuatkan surat. */
    public function show(AjuanSurat $ajuanSurat)
    {
        $nomorUrutBerikutnya = \App\Models\SuratKeluar::nomorUrutTerbesar() + 1;
        $kodeBaku = PengaturanSurat::ambil()->kode_baku;

        return view('ajuan-surat.tu-detail', [
            'ajuan' => $ajuanSurat,
            'nomorUrutBerikutnya' => $nomorUrutBerikutnya,
            'kodeBaku' => $kodeBaku,
        ]);
    }

    /** Generate surat (.docx, isi langsung ke template Word asli) dari data ajuan - khusus jenis SPPD dulu. */
    public function buatSurat(Request $request, AjuanSurat $ajuanSurat)
    {
        $request->validate([
            'kode_umum' => ['required', 'string', 'max:30'],
            'nomor_urut' => ['required', 'integer', 'min:1'],
        ]);

        $susunan = \App\Models\SuratKeluar::susunKode(
            $request->input('kode_umum'),
            (int) $request->input('nomor_urut'),
            now()
        );
        $nomorSuratLengkap = $susunan['kode_surat'];

        $pengaturan = PengaturanSurat::ambil();
        $guru = $ajuanSurat->guru;
        $member = $guru->member;
        $data = $ajuanSurat->data;

        $jamSelesai = !empty($data['jam_selesai']) ? $data['jam_selesai'] : 'selesai';

        $isian = [
            'nomersurat' => $nomorSuratLengkap,
            'namaguru' => $guru->nama ?? '-',
            'nama' => $guru->nama ?? '-', // dipakai di halaman SPD (placeholder beda dari Surat Tugas)
            'nip' => $guru->nip ?? '-',
            'pangkat' => $member->pangkat ?? '-',
            'pangkatjabat' => $member->jabatan_dinas ?? '-',
            'hari' => $data['hari'] ?? '-',
            'mulai' => $data['jam_mulai'] ?? '-',
            'selesai' => $jamSelesai,
            'tempat' => $data['tempat_tujuan'] ?? '-',
            'tema' => $data['tema'] ?? '-',
            'deskrpsi' => $data['isian_form'] ?? '-',
            'tglundangan' => !empty($data['tanggal_dasar'])
                ? \Carbon\Carbon::parse($data['tanggal_dasar'])->translatedFormat('d F Y')
                : '-',
            'noundangan' => !empty($data['nomor_surat_dasar']) ? 'No. '.$data['nomor_surat_dasar'] : '',
            'tanggalsurat' => \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y'),
            'tanggal' => \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y'),
            'tanggalselesai' => !empty($data['tanggal_selesai']) ? \Carbon\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d F Y') : '-',
            'totalhari' => (string) ($data['total_hari'] ?? 1),
        ];

        $namaFile = $this->namaFileSurat($guru, $data);
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('ajuan-surat');
        $outputPath = storage_path('app/public/ajuan-surat/'.$namaFile);

        $berhasil = \App\Services\DocxMergeService::isi(
            resource_path('templates/sppd_template.docx'),
            $isian,
            $outputPath
        );

        if (!$berhasil) {
            return back()->with('status', 'Gagal membuat surat - cek template docx di server.');
        }

        $ajuanSurat->update([
            'status' => 'selesai',
            'nomor_surat' => $nomorSuratLengkap,
            'file_pdf' => 'ajuan-surat/'.$namaFile,
            'diproses_oleh' => Auth::guard('member')->id(),
            'diproses_at' => now(),
        ]);

        return redirect()->route('surat-tu.show', $ajuanSurat)->with('status', 'Surat berhasil dibuat untuk '.($guru->nama ?? '-').' (format .docx, siap dicetak/diubah PDF manual).');
    }

    /**
     * Nama file: sppd_{panggilan}_{judul}_{tanggal}.docx - "panggilan" dari
     * member.panggilan, "judul" dari tema kegiatan. Karakter yang tidak aman
     * untuk nama file (spasi, slash, dll) diganti garis bawah.
     */
    private function namaFileSurat($guru, array $data): string
    {
        $member = $guru->member;
        $panggilan = $member->panggilan ?: $guru->nama;
        $judul = $data['tema'] ?? 'sppd';
        $tanggal = \Carbon\Carbon::parse($data['tanggal'] ?? now())->format('Ymd');

        $bersihkan = fn ($teks) => trim(preg_replace('/[^A-Za-z0-9]+/', '_', $teks), '_');

        $judulBersih = substr($bersihkan($judul), 0, 10);

        return 'SPPD_'.$bersihkan($panggilan).'_'.$judulBersih.'_'.$tanggal.'.docx';
    }
}
