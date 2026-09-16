<?php

namespace App\Http\Controllers;

use App\Models\PipSiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PipSiswaController extends Controller
{
    /** List PIP - Kesiswaan lihat semua, Wali Kelas cuma kelasnya sendiri. */
    public function index(Request $request)
    {
        $member = Auth::guard('member')->user();
        $query = PipSiswa::with('siswa')->whereHas('siswa');

        if (!$member->hasRole('kesiswaan')) {
            // Berarti wali kelas - filter ke kelasnya sendiri saja.
            $kelasWali = trim((string) $member->walikelas);
            abort_if($kelasWali === '', 403, 'Akun ini tidak terhubung ke kelas manapun.');
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $kelasWali));
        } elseif ($request->filled('kelas')) {
            $query->whereHas('siswa', fn ($q) => $q->where('kelas', $request->input('kelas')));
        }

        if ($request->input('status') === 'sudah') {
            $query->where('status_cair', 'like', '%sudah%');
        } elseif ($request->input('status') === 'belum') {
            $query->where('status_cair', 'like', '%belum%');
        }

        $daftar = $query->get()->sortBy(fn ($p) => $p->siswa->nama_lengkap ?? '')->values();

        $daftarKelas = $member->hasRole('kesiswaan')
            ? \App\Models\Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas')
            : collect();

        return view('pip.index', compact('daftar', 'daftarKelas'));
    }

    /** List teks siap-copy (Nama / Kelas - Status) buat wali kelas kirim ke WhatsApp - default filter Belum Cair. */
    public function listTeks(Request $request)
    {
        $member = Auth::guard('member')->user();
        $kelasWali = trim((string) $member->walikelas);
        abort_if($kelasWali === '', 403, 'Akun ini tidak terhubung ke kelas manapun.');

        $status = $request->input('status', 'belum');

        $daftar = PipSiswa::with('siswa')
            ->whereHas('siswa', fn ($q) => $q->where('kelas', $kelasWali))
            ->when($status === 'sudah', fn ($q) => $q->where('status_cair', 'like', '%sudah%'))
            ->when($status === 'belum', fn ($q) => $q->where('status_cair', 'like', '%belum%'))
            ->get()
            ->sortBy(fn ($p) => $p->siswa->nama_lengkap ?? '')
            ->values();

        $teks = $daftar->map(fn ($p) => "{$p->siswa->nama_lengkap} / {$p->siswa->kelas} - {$p->status_cair}")->implode("\n");

        return view('pip.list-teks', compact('daftar', 'teks', 'kelasWali', 'status'));
    }

    /** Detail lengkap 1 data PIP. */
    public function show(PipSiswa $pipSiswa)
    {
        $member = Auth::guard('member')->user();

        if (!$member->hasRole('kesiswaan')) {
            $kelasWali = trim((string) $member->walikelas);
            abort_unless($pipSiswa->siswa && $pipSiswa->siswa->kelas === $kelasWali, 403, 'Bukan siswa di kelas Anda.');
        }

        return view('pip.detail', ['p' => $pipSiswa]);
    }
}
