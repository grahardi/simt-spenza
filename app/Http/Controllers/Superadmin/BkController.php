<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Bimbingan;
use Illuminate\Http\Request;

class BkController extends Controller
{
    public function index(Request $request)
    {
        $bk = Bimbingan::with('siswa')
            ->when($request->filled('cari'), fn ($q) => $q->whereHas('siswa', fn ($s) => $s->where('nama_lengkap', 'like', '%'.$request->input('cari').'%')))
            ->orderByDesc('tgl_bimbingan')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.bk.index', compact('bk'));
    }

    public function edit(Bimbingan $bkItem)
    {
        return view('superadmin.bk.form', ['bk' => $bkItem]);
    }

    public function update(Request $request, Bimbingan $bkItem)
    {
        $data = $request->validate([
            'tgl_bimbingan' => ['required', 'date'],
            'kategori' => ['required', 'in:Pendampingan,Verifikasi,Pelanggaran,Lainnya'],
            'Keterangan' => ['nullable', 'string', 'max:255'],
            'Tindakan' => ['required', 'in:Tidak Ada,Notifikasi,Peringatan,Tindakan'],
        ]);

        $bkItem->update($data);

        return redirect()->route('superadmin.bk.index')->with('status', 'Data bimbingan berhasil diperbarui.');
    }

    public function destroy(Bimbingan $bkItem)
    {
        $bkItem->delete();

        return back()->with('status', 'Data bimbingan berhasil dihapus.');
    }
}
