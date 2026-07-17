<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogAktivitasController extends Controller
{
    /**
     * Log kegiatan, dikelompokkan per kategori supaya tidak jadi 1 daftar
     * raksasa - tab: Absensi, Pelanggaran, Keterlambatan, Sistem/Lainnya.
     */
    public function index(Request $request)
    {
        $kategori = $request->input('kategori', 'absensi');
        if (!in_array($kategori, LogAktivitas::KATEGORI, true)) {
            $kategori = 'absensi';
        }

        $log = LogAktivitas::with('member')
            ->where('kategori', $kategori)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        $jumlahPerKategori = LogAktivitas::selectRaw('kategori, count(*) as jumlah')
            ->groupBy('kategori')
            ->pluck('jumlah', 'kategori');

        return view('superadmin.log.index', compact('log', 'kategori', 'jumlahPerKategori'));
    }
}
