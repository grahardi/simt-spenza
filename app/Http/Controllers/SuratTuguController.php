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

    /** Generate PDF surat dari data ajuan (khusus jenis SPPD dulu). */
    public function buatSurat(Request $request, AjuanSurat $ajuanSurat)
    {
        $request->validate(['nomor_surat' => ['required', 'string', 'max:100']]);

        $pengaturan = PengaturanSurat::ambil();
        $guru = $ajuanSurat->guru;
        $data = $ajuanSurat->data;

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('ajuan-surat.pdf-sppd', [
            'ajuan' => $ajuanSurat,
            'guru' => $guru,
            'data' => $data,
            'pengaturan' => $pengaturan,
            'nomorSurat' => $request->input('nomor_surat'),
        ])->setPaper('a4', 'portrait');

        $namaFile = 'sppd-'.$ajuanSurat->id.'-'.now()->format('Ymd').'.pdf';
        \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory('ajuan-surat');
        $pdf->save(storage_path('app/public/ajuan-surat/'.$namaFile));

        $ajuanSurat->update([
            'status' => 'selesai',
            'nomor_surat' => $request->input('nomor_surat'),
            'file_pdf' => 'ajuan-surat/'.$namaFile,
            'diproses_oleh' => Auth::guard('member')->id(),
            'diproses_at' => now(),
        ]);

        return redirect()->route('surat-tu.index')->with('status', 'Surat berhasil dibuat untuk '.($guru->nama ?? '-').'.');
    }
}
