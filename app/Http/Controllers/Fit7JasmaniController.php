<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class Fit7JasmaniController extends Controller
{
    /** Halaman intro/landing - penjelasan aplikasi. */
    public function index()
    {
        $this->cekAksesPjo();

        return view('fit7-jasmani.intro');
    }

    /** Halaman aplikasi sebenarnya (kalkulator, rekap, timer, materi) - halaman terpisah. */
    public function aplikasi()
    {
        $this->cekAksesPjo();

        return view('fit7-jasmani.aplikasi');
    }

    private function cekAksesPjo(): void
    {
        $member = Auth::guard('member')->user();
        $iniGuruPjo = $member->dataGuru && str_contains(strtoupper((string) $member->dataGuru->jabatan), 'PJO');

        abort_unless($iniGuruPjo || $member->hasRole('superadmin'), 403, 'Fitur ini khusus guru mapel PJO.');
    }

    /** Daftar nama siswa 1 kelas (data asli, bukan ketik manual) - dipakai dropdown lewat AJAX. */
    public function siswaKelas(string $kelas)
    {
        $daftar = Siswa::where('kelas', $kelas)
            ->orderBy('nama_lengkap')
            ->get(['nama_lengkap as nama', 'tanggal_lahir'])
            ->map(fn ($s) => [
                'nama' => $s->nama,
                'usia' => $s->tanggal_lahir ? (int) floor($s->tanggal_lahir->age) : null,
            ]);

        return response()->json($daftar);
    }
}
