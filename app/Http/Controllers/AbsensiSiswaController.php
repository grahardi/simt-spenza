<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiSiswaController extends Controller
{
    /**
     * Pengganti absenjelas.php.
     * Perbaikan dari versi lama:
     * - Query pakai Eloquent (aman dari SQL injection, tidak ada lagi string interpolation).
     * - Output otomatis di-escape oleh Blade (aman dari XSS), tidak perlu echo manual.
     * - Pagination pakai Laravel paginate(), bukan hitung manual $lim.
     */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        // Senin -> "kemarin" dihitung mundur 2 hari (Sabtu), sesuai logika hari.php lama.
        $tanggalSebelumnya = $tanggal->isMonday()
            ? $tanggal->copy()->subDays(2)
            : $tanggal->copy()->subDay();

        $absensi = AbsenSiswa::with(['siswa.kelas'])
            ->whereDate('tanggal', $tanggal)
            ->orderBy(
                \App\Models\Siswa::select('kelas_id')
                    ->whereColumn('siswa.id', 'absen_siswa.siswa_id')
            )
            ->paginate(20)
            ->withQueryString();

        // Absensi hari sebelumnya untuk tiap siswa yang tampil, diambil dalam 1 query
        // (menggantikan pola "include cekabsen.php di dalam loop" yang dulu query per-baris).
        $siswaIds = $absensi->pluck('siswa_id');
        $absenSebelumnya = AbsenSiswa::whereIn('siswa_id', $siswaIds)
            ->whereDate('tanggal', $tanggalSebelumnya)
            ->get()
            ->keyBy('siswa_id');

        return view('absensi.index', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'absenSebelumnya' => $absenSebelumnya,
        ]);
    }
}
