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

    /** Detail lengkap 1 ajuan - sebelum dibuatkan surat. */
    public function show(AjuanSurat $ajuanSurat)
    {
        return view('ajuan-surat.tu-detail', ['ajuan' => $ajuanSurat]);
    }

    /** Generate surat (.docx, isi langsung ke template Word asli) dari data ajuan - khusus jenis SPPD dulu. */
    public function buatSurat(Request $request, AjuanSurat $ajuanSurat)
    {
        $request->validate(['nomor_surat' => ['required', 'string', 'max:100']]);

        $pengaturan = PengaturanSurat::ambil();
        $guru = $ajuanSurat->guru;
        $member = $guru->member;
        $data = $ajuanSurat->data;

        $jamSelesai = !empty($data['jam_selesai']) ? $data['jam_selesai'] : '(selesai)';

        $isian = [
            'nomersurat' => $request->input('nomor_surat'),
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
            'tanggalsurat' => \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y'),
            'tanggal' => \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y'),
            'tanggalselesai' => !empty($data['tanggal_selesai']) ? \Carbon\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d F Y') : '-',
            'totalhari' => (string) ($data['total_hari'] ?? 1),
        ];

        $namaFile = 'sppd-'.$ajuanSurat->id.'-'.now()->format('Ymd').'.docx';
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
            'nomor_surat' => $request->input('nomor_surat'),
            'file_pdf' => 'ajuan-surat/'.$namaFile,
            'diproses_oleh' => Auth::guard('member')->id(),
            'diproses_at' => now(),
        ]);

        return redirect()->route('surat-tu.index')->with('status', 'Surat berhasil dibuat untuk '.($guru->nama ?? '-').' (format .docx, siap dicetak/diubah PDF manual).');
    }
}
