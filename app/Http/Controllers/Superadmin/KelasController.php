<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    /**
     * CRUD Data Kelas - Tambah/Edit saja. Tanpa hapus, karena kelas yang
     * sudah dipakai siswa/jadwal/dll berisiko jadi data yatim kalau dihapus.
     */
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->paginate(20);

        return view('superadmin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('superadmin.kelas.form', ['kelas' => new Kelas()]);
    }

    public function store(Request $request)
    {
        Kelas::create($this->validated($request));

        return redirect()->route('superadmin.kelas.index')->with('status', 'Kelas baru berhasil ditambahkan.');
    }

    public function edit(Kelas $kelas)
    {
        return view('superadmin.kelas.form', compact('kelas'));
    }

    public function update(Request $request, Kelas $kelas)
    {
        $kelas->update($this->validated($request));

        return redirect()->route('superadmin.kelas.index')->with('status', 'Data kelas berhasil diperbarui.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nama_kelas' => ['required', 'string', 'max:10'],
            'jumlah' => ['nullable', 'integer'],
        ]);
    }
}
