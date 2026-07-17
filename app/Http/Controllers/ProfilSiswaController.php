<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Keterlambatan;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ProfilSiswaController extends Controller
{
    /**
     * Pengganti tampil_detail.php (index.php?halaman=detail&id=...) -
     * profil lengkap 1 siswa: biodata + riwayat absensi + keterlambatan +
     * pelanggaran, masing-masing dipaginasi 20/halaman.
     */
    public function show(Request $request, Siswa $siswa)
    {
        $absensi = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->paginate(20, ['*'], 'p_absensi')
            ->withQueryString();

        $keterlambatan = Keterlambatan::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->paginate(20, ['*'], 'p_telat')
            ->withQueryString();

        // Pelanggaran: arsip per tahun ajaran (1 Juli - 30 Juni), sama seperti
        // halaman List Pelanggaran Tatib. Ganti tahun = reload halaman (bukan
        // JS toggle lagi) supaya paginasi per tahun bisa berjalan benar.
        $sekarang = Carbon::now('Asia/Jakarta');
        $tahunAjaranSekarang = $sekarang->month >= 7 ? $sekarang->year : $sekarang->year - 1;
        $tahunMulai = 2025;

        $tahunAjaran = (int) ($request->input('tahun') ?? $tahunAjaranSekarang);
        $tahunAjaran = max($tahunMulai, min($tahunAjaran, $tahunAjaranSekarang));
        $daftarTahunAjaran = range($tahunAjaranSekarang, $tahunMulai, -1);

        $mulai = Carbon::create($tahunAjaran, 7, 1)->startOfDay();
        $selesai = Carbon::create($tahunAjaran + 1, 6, 30)->endOfDay();

        $pelanggaran = Pelanggaran::where('id_siswa', $siswa->id_member)
            ->whereBetween('tgl_pelanggaran', [$mulai->toDateString(), $selesai->toDateString()])
            ->orderByDesc('tgl_pelanggaran')
            ->paginate(20, ['*'], 'p_pelanggaran')
            ->withQueryString();

        $totalPoinTahunIni = Pelanggaran::where('id_siswa', $siswa->id_member)
            ->whereBetween('tgl_pelanggaran', [$mulai->toDateString(), $selesai->toDateString()])
            ->get()
            ->sum(fn ($p) => is_numeric($p->poin) ? (int) $p->poin : 0);

        return view('siswa.profil', compact(
            'siswa', 'absensi', 'keterlambatan', 'pelanggaran',
            'tahunAjaran', 'daftarTahunAjaran', 'totalPoinTahunIni'
        ));
    }
}
