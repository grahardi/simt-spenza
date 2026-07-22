<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class GuruWaliController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('guruWali')
            ->when($request->filled('cari'), fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->input('cari').'%'))
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->input('kelas')))
            ->when($request->filled('id_guru_wali'), fn ($q) => $q->where('id_guru_wali', $request->input('id_guru_wali')))
            ->when($request->input('status') === 'belum', fn ($q) => $q->whereNull('id_guru_wali'));

        // Urut berdasarkan nama guru wali (siswa tanpa wali ditaruh di akhir),
        // baru diikutkan urut kelas & nama sebagai pengurut kedua.
        $query->leftJoin('guru', 'datasiswa.id_guru_wali', '=', 'guru.id_guru')
            ->orderByRaw('guru.nama IS NULL')
            ->orderBy('guru.nama')
            ->orderBy('datasiswa.kelas')
            ->orderBy('datasiswa.nama_lengkap')
            ->select('datasiswa.*');

        $siswa = $query->paginate(30)->withQueryString();

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('superadmin.guru-wali.index', compact('siswa', 'daftarKelas', 'daftarGuru'));
    }

    /** Assign guru wali ke banyak siswa sekaligus (checkbox + pilih guru). */
    public function assign(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', 'array', 'min:1'],
            'siswa_id.*' => ['integer'],
            'id_guru' => ['required', 'integer', 'exists:guru,id_guru'],
        ]);

        Siswa::whereIn('id_member', $data['siswa_id'])->update(['id_guru_wali' => $data['id_guru']]);

        $guru = Guru::find($data['id_guru']);

        return back()->with('status', count($data['siswa_id']).' siswa berhasil di-assign ke wali '.($guru->nama ?? '-').'.');
    }

    /** Lepas wali dari 1 siswa. */
    public function lepas(Siswa $siswa)
    {
        $siswa->update(['id_guru_wali' => null]);

        return back()->with('status', 'Wali '.$siswa->nama_lengkap.' berhasil dilepas.');
    }
}
