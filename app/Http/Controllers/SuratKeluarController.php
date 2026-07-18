<?php

namespace App\Http\Controllers;

use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $surat = SuratKeluar::query()
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where('perihal', 'like', '%'.$cari.'%')
                    ->orWhere('tujuan_surat', 'like', '%'.$cari.'%')
                    ->orWhere('kode_surat', 'like', '%'.$cari.'%');
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('persuratan.surat-keluar.index', compact('surat'));
    }

    /** Nomor surat berikutnya sudah dihitung & ditampilkan di form (preview), belum tersimpan sampai submit. */
    public function create()
    {
        $preview = SuratKeluar::nomorBerikutnya();

        return view('persuratan.surat-keluar.form', ['item' => new SuratKeluar(), 'preview' => $preview]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        // Hitung ulang nomor PAS SEBELUM simpan (bukan pakai preview lama) - mencegah
        // nomor dobel kalau ada 2 orang buka form Tambah Surat bersamaan.
        $nomor = SuratKeluar::nomorBerikutnya($data['tanggal_surat']);
        $data = array_merge($data, $nomor);

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('surat-keluar', 'public');
        }

        $data['dibuat_oleh'] = Auth::guard('member')->id();

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')->with('status', 'Surat keluar berhasil dibuat dengan nomor '.$data['kode_surat'].'.');
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        return view('persuratan.surat-keluar.form', ['item' => $suratKeluar, 'preview' => null]);
    }

    public function update(Request $request, SuratKeluar $suratKeluar)
    {
        $data = $this->validated($request);

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('surat-keluar', 'public');
        }

        $suratKeluar->update($data);

        return redirect()->route('surat-keluar.index')->with('status', 'Surat keluar berhasil diperbarui.');
    }

    public function destroy(SuratKeluar $suratKeluar)
    {
        $suratKeluar->delete();

        return redirect()->route('surat-keluar.index')->with('status', 'Surat keluar berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'tanggal_surat' => ['required', 'date'],
            'tujuan_surat' => ['required', 'string', 'max:150'],
            'perihal' => ['required', 'string', 'max:200'],
        ]);
    }
}
