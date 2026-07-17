<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ArsipSuratController extends Controller
{
    /**
     * List surat/berkas (foto bukti sakit/ijin) yang sudah diupload untuk
     * siswa yang terabsen resmi - diambil dari kolom absen_siswa.gambar.
     */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $arsip = AbsenSiswa::with('siswa')
            ->whereDate('tgl_absen', $tanggal)
            ->whereNotNull('gambar')
            ->where('gambar', '!=', '')
            ->orderByDesc('id_absen_siswa')
            ->paginate(20)
            ->withQueryString();

        return view('arsip-surat.index', compact('arsip', 'tanggal'));
    }
}
