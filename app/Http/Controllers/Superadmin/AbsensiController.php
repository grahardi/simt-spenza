<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $absensi = AbsenSiswa::with('siswa')
            ->when($request->filled('tgl'), fn ($q) => $q->whereDate('tgl_absen', $request->input('tgl')))
            ->when($request->filled('kelas'), fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('kelas', $request->input('kelas'))))
            ->orderByDesc('tgl_absen')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.absensi.index', compact('absensi'));
    }

    public function edit(AbsenSiswa $absen)
    {
        return view('superadmin.absensi.form', compact('absen'));
    }

    public function update(Request $request, AbsenSiswa $absen)
    {
        $data = $request->validate([
            'tgl_absen' => ['required', 'date'],
            'keterangan' => ['required', 'in:h,s,i,a,d'],
            'tambahan' => ['nullable', 'string', 'max:100'],
        ]);

        $absen->update($data);

        return redirect()->route('superadmin.absensi.index')->with('status', 'Data absensi berhasil diperbarui.');
    }

    public function destroy(AbsenSiswa $absen)
    {
        $absen->delete();

        return back()->with('status', 'Data absensi berhasil dihapus.');
    }
}
