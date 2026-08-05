<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AbsensiGuru;
use Illuminate\Http\Request;

class AbsensiGuruController extends Controller
{
    public function index(Request $request)
    {
        $absensi = AbsensiGuru::with('guru')
            ->when($request->filled('tgl'), fn ($q) => $q->whereDate('tanggal', $request->input('tgl')))
            ->when($request->filled('cari'), fn ($q) => $q->whereHas('guru', fn ($g) => $g->where('nama', 'like', '%'.$request->input('cari').'%')))
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.absensi-guru.index', compact('absensi'));
    }

    public function edit(AbsensiGuru $absensiGuru)
    {
        return view('superadmin.absensi-guru.form', ['absen' => $absensiGuru]);
    }

    public function update(Request $request, AbsensiGuru $absensiGuru)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,a,d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $absensiGuru->update($data);

        return redirect()->route('superadmin.absensi-guru.index')->with('status', 'Data absensi guru berhasil diperbarui.');
    }

    public function destroy(AbsensiGuru $absensiGuru)
    {
        if ($absensiGuru->foto) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($absensiGuru->foto);
        }
        $absensiGuru->delete();

        return back()->with('status', 'Data absensi guru berhasil dihapus.');
    }
}
