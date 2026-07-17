<?php

namespace App\Http\Controllers;

use App\Models\DknSubject;
use App\Models\DknUpload;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DknKelasController extends Controller
{
    /**
     * Pengganti fitur DKN Kelas (dari tabel dkn_uploads/dkn_subjects) -
     * wali kelas upload berkas DKN (Daftar Kumpulan Nilai) per mata
     * pelajaran untuk kelas yang diampu.
     */
    public function index()
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $daftarMapel = DknSubject::orderBy('nama_mapel')->get();

        $daftarUpload = DknUpload::where('nama_kelas', $kelas)
            ->orderByDesc('uploaded_at')
            ->get();

        return view('dkn.index', compact('kelas', 'daftarMapel', 'daftarUpload'));
    }

    /** Simpan berkas DKN untuk 1 mapel di kelas yang diampu wali kelas. */
    public function simpan(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $data = $request->validate([
            'kode_mapel' => ['required', 'string', 'max:10'],
            'file' => ['required', 'file', 'mimes:pdf,xlsx,xls', 'max:10240'],
        ]);

        $path = $request->file('file')->store('dkn', 'public');

        DknUpload::updateOrCreate(
            ['kode_mapel' => $data['kode_mapel'], 'nama_kelas' => $kelas],
            ['nama_file' => $path, 'uploaded_at' => now()]
        );

        return back()->with('status', 'Berkas DKN berhasil diupload.');
    }
}
