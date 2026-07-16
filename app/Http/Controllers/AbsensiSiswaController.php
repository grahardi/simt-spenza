<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiSiswaController extends Controller
{
    /**
     * Pengganti absenjelas.php.
     * Disesuaikan dengan struktur asli dari absen26.sql:
     * tbl_member.id_member <-> absen_siswa.id_siswa, kolom tgl_absen & keterangan.
     * Perbaikan dari versi lama: Eloquent (aman dari SQL injection), Blade
     * auto-escape (aman dari XSS), paginate() bukan hitung manual $lim.
     */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $tanggalSebelumnya = $tanggal->isMonday()
            ? $tanggal->copy()->subDays(2)
            : $tanggal->copy()->subDay();

        $absensi = AbsenSiswa::with('siswa')
            ->whereDate('tgl_absen', $tanggal)
            ->orderBy(
                \App\Models\Siswa::select('kelas')
                    ->whereColumn('datasiswa.id_member', 'absen_siswa.id_siswa')
            )
            ->paginate(20)
            ->withQueryString();

        $siswaIds = $absensi->pluck('id_siswa');
        $absenSebelumnya = AbsenSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tgl_absen', $tanggalSebelumnya)
            ->get()
            ->keyBy('id_siswa');

        return view('absensi.index', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'absenSebelumnya' => $absenSebelumnya,
        ]);
    }
}
