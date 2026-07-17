<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiBulananController extends Controller
{
    /** Rekap absensi bulanan per kelas - total Sakit/Ijin/Alfa/Dispensasi. */
    public function index(Request $request)
    {
        $bulan = $request->date('bulan') ?? Carbon::today();
        $bulan = Carbon::parse($bulan)->startOfMonth();
        $akhirBulan = $bulan->copy()->endOfMonth();

        $rekap = AbsenSiswa::join('datasiswa', 'datasiswa.id_member', '=', 'absen_siswa.id_siswa')
            ->whereBetween('tgl_absen', [$bulan->toDateString(), $akhirBulan->toDateString()])
            ->selectRaw('datasiswa.kelas, absen_siswa.keterangan, count(*) as jumlah')
            ->groupBy('datasiswa.kelas', 'absen_siswa.keterangan')
            ->get()
            ->groupBy('kelas');

        return view('absensi.bulanan', compact('rekap', 'bulan'));
    }
}
