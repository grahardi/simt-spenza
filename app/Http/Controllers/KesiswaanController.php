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
     * Rekap absensi per hari (S/I/A/D), diurutkan tanggal terbaru dulu,
     * paginasi 15 baris per halaman supaya gampang navigasi mundur per minggu.
     */
    public function rekapMingguan(Request $request)
    {
        $rekap = AbsenSiswa::query()
            ->select('tgl_absen')
            ->selectRaw("SUM(keterangan = 's') as sakit")
            ->selectRaw("SUM(keterangan = 'i') as ijin")
            ->selectRaw("SUM(keterangan = 'a') as alfa")
            ->selectRaw("SUM(keterangan = 'd') as dispensasi")
            ->groupBy('tgl_absen')
            ->orderByDesc('tgl_absen')
            ->paginate(15)
            ->withQueryString();

        return view('kesiswaan.rekap-mingguan', compact('rekap'));
    }
}
