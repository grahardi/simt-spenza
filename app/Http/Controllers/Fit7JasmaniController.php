<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class Fit7JasmaniController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();
        $iniGuruPjo = $member->dataGuru && str_contains(strtoupper((string) $member->dataGuru->jabatan), 'PJO');

        abort_unless($iniGuruPjo || $member->hasRole('superadmin'), 403, 'Fitur ini khusus guru mapel PJO.');

        return view('fit7-jasmani.index');
    }

    /** Daftar nama siswa 1 kelas (data asli, bukan ketik manual) - dipakai dropdown lewat AJAX. */
    public function siswaKelas(string $kelas)
    {
        $daftar = Siswa::where('kelas', $kelas)
            ->orderBy('nama_lengkap')
            ->get(['nama_lengkap as nama']);

        return response()->json($daftar);
    }
}
