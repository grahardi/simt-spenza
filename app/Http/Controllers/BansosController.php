<?php

namespace App\Http\Controllers;

use App\Models\BansosAjuan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BansosController extends Controller
{
    const MAKS_PER_KELAS = 5;

    /** Halaman wali kelas - centang max 5 anak di kelasnya untuk diajukan Bansos. */
    public function ajukan()
    {
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);
        abort_if($kelas === '', 403, 'Akun ini tidak terhubung ke kelas manapun sebagai wali kelas.');

        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap')->get();
        $idSudahDiajukan = BansosAjuan::where('kelas', $kelas)->pluck('id_siswa')->toArray();

        return view('bansos.ajukan', compact('kelas', 'siswa', 'idSudahDiajukan'));
    }

    public function simpanAjuan(Request $request)
    {
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);
        abort_if($kelas === '', 403, 'Akun ini tidak terhubung ke kelas manapun sebagai wali kelas.');

        $data = $request->validate([
            'siswa' => ['nullable', 'array', 'max:'.self::MAKS_PER_KELAS],
            'siswa.*' => ['integer'],
        ]);

        $idDipilih = $data['siswa'] ?? [];

        // Pastikan semua siswa yang dipilih memang anak kelas ini (bukan kelas lain).
        $idValid = Siswa::where('kelas', $kelas)->whereIn('id_member', $idDipilih)->pluck('id_member')->toArray();

        // Reset dulu ajuan kelas ini, baru isi ulang sesuai pilihan sekarang (maks 5 tetap dijamin di query di atas).
        BansosAjuan::where('kelas', $kelas)->delete();

        foreach ($idValid as $idSiswa) {
            BansosAjuan::create([
                'id_siswa' => $idSiswa,
                'kelas' => $kelas,
                'diajukan_oleh' => $member->id,
            ]);
        }

        return back()->with('status', 'Ajuan Bansos kelas '.$kelas.' berhasil disimpan ('.count($idValid).' siswa).');
    }

    /** Rekap semua penerima Bansos - khusus Tatib & Kesiswaan. */
    public function rekap(Request $request)
    {
        $rekap = BansosAjuan::with('siswa')
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->input('kelas')))
            ->orderBy('kelas')
            ->orderBy('id_siswa')
            ->paginate(20)
            ->withQueryString();

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('bansos.rekap', compact('rekap', 'daftarKelas'));
    }
}
