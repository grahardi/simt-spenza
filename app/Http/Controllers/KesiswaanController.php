<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KesiswaanController extends Controller
{
    /**
     * List siswa yang Sakit ATAU Alfa (gabungan) 3 hari atau lebih dalam
     * MINGGU INI (Senin-Minggu berjalan). READ-ONLY - tidak ada aksi ubah
     * data apapun di fitur ini, cuma laporan buat dilihat.
     */
    public function tidakMasuk(Request $request)
    {
        $mingguDipilih = $request->date('minggu') ?? Carbon::now('Asia/Jakarta');
        $awalMinggu = Carbon::parse($mingguDipilih)->startOfWeek(Carbon::MONDAY);
        $akhirMinggu = $awalMinggu->copy()->endOfWeek(Carbon::SUNDAY);

        $daftar = AbsenSiswa::with('siswa')
            ->whereIn('keterangan', ['a', 's'])
            ->whereBetween('tgl_absen', [$awalMinggu->format('Y-m-d'), $akhirMinggu->format('Y-m-d')])
            ->orderBy('tgl_absen')
            ->get()
            ->groupBy('id_siswa')
            ->filter(fn ($grup) => $grup->count() >= 3)
            ->map(fn ($grup) => (object) [
                'siswa' => $grup->first()->siswa,
                'jumlah' => $grup->count(),
                'detail' => $grup,
            ])
            ->sortByDesc('jumlah')
            ->values();

        return view('kesiswaan.tidak-masuk', compact('daftar', 'awalMinggu', 'akhirMinggu'));
    }

    /**
     * Rekap absensi per SISWA dalam 1 minggu (Senin-Minggu) - kolom Sakit/
     * Ijin/Alfa terpisah (Dispensasi TIDAK dihitung, bukan kategori "tidak
     * masuk"). Siswa dengan total S+I+A >= 3 ditandai warna warning di view.
     */
    public function rekapMingguan(Request $request)
    {
        $mingguDipilih = $request->date('minggu') ?? Carbon::now('Asia/Jakarta');
        $awalMinggu = Carbon::parse($mingguDipilih)->startOfWeek(Carbon::MONDAY);
        $akhirMinggu = $awalMinggu->copy()->endOfWeek(Carbon::SUNDAY);

        $rekap = AbsenSiswa::with('siswa')
            ->whereIn('keterangan', ['s', 'i', 'a']) // dispensasi (d) sengaja tidak dihitung
            ->whereBetween('tgl_absen', [$awalMinggu->format('Y-m-d'), $akhirMinggu->format('Y-m-d')])
            ->get()
            ->groupBy('id_siswa')
            ->map(function ($grup) {
                return (object) [
                    'siswa' => $grup->first()->siswa,
                    'sakit' => $grup->where('keterangan', 's')->count(),
                    'ijin' => $grup->where('keterangan', 'i')->count(),
                    'alfa' => $grup->where('keterangan', 'a')->count(),
                ];
            })
            ->sortByDesc(fn ($r) => $r->sakit + $r->ijin + $r->alfa)
            ->values();

        return view('kesiswaan.rekap-mingguan', compact('rekap', 'awalMinggu', 'akhirMinggu'));
    }
}
