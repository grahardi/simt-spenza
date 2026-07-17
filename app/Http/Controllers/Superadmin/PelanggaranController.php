<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Pelanggaran;
use Illuminate\Http\Request;

class PelanggaranController extends Controller
{
    public function index(Request $request)
    {
        $pelanggaran = Pelanggaran::with('siswa')
            ->when($request->filled('cari'), fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('nama_lengkap', 'like', '%'.$request->input('cari').'%')))
            ->orderByDesc('tgl_pelanggaran')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.pelanggaran.index', compact('pelanggaran'));
    }

    public function edit(Pelanggaran $pelanggaran)
    {
        return view('superadmin.pelanggaran.form', compact('pelanggaran'));
    }

    public function update(Request $request, Pelanggaran $pelanggaran)
    {
        $data = $request->validate([
            'tgl_pelanggaran' => ['required', 'date'],
            'kategori' => ['required', 'in:Peringatan,Ringan,Sedang,Berat'],
            'keterangan' => ['nullable', 'string', 'max:200'],
            'poin' => ['nullable', 'numeric'],
            'penanganan' => ['nullable', 'string', 'max:50'],
        ]);

        $pelanggaran->update($data);

        return redirect()->route('superadmin.pelanggaran.index')->with('status', 'Data pelanggaran berhasil diperbarui.');
    }

    public function destroy(Pelanggaran $pelanggaran)
    {
        $pelanggaran->delete();

        return back()->with('status', 'Data pelanggaran berhasil dihapus.');
    }
}
