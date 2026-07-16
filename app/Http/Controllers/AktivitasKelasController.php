<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Member;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasKelasController extends Controller
{
    /**
     * Aktivitas kelas untuk wali kelas - rekap absensi hari ini untuk siswa
     * di kelas yang diampu. Kolom `member.walikelas` di sistem lama berisi
     * NAMA KELAS langsung (bukan flag 0/1), contoh: "7 - A" - dipakai untuk
     * mencocokkan siswa di kelas yang sama (lihat home.php/kebersihan.php lama).
     */
    public function index(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $siswa = Siswa::where('kelas', $kelas)
            ->orderBy('nama_lengkap')
            ->get();

        $absen = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $tanggal)
            ->get()
            ->keyBy('id_siswa');

        $siswa->each(function ($s) use ($absen) {
            $s->absenHariIni = $absen->get($s->id_member);
        });

        $rekap = [
            'hadir' => $siswa->filter(fn ($s) => !$s->absenHariIni)->count(),
            'sakit' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 's')->count(),
            'ijin' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'i')->count(),
            'alfa' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'a')->count(),
            'dispensasi' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'd')->count(),
        ];

        return view('kelas.aktivitas', compact('siswa', 'kelas', 'tanggal', 'rekap'));
    }
}
