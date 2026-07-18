<?php

namespace App\Http\Controllers;

use App\Models\KategoriSurat;
use App\Models\SuratKeluar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SuratKeluarController extends Controller
{
    public function index(Request $request)
    {
        $surat = SuratKeluar::with('kategori')
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

    public function create()
    {
        $daftarKategori = KategoriSurat::orderBy('nama')->get();
        $nomorUrutBerikutnya = SuratKeluar::nomorUrutTerbesar() + 1;

        return view('persuratan.surat-keluar.form', [
            'item' => new SuratKeluar(),
            'daftarKategori' => $daftarKategori,
            'nomorUrutBerikutnya' => $nomorUrutBerikutnya,
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        $request->validate([
            'mode_nomor' => ['required', 'in:auto,manual'],
            'nomor_urut_manual' => ['required_if:mode_nomor,manual', 'nullable', 'integer', 'min:1', 'unique:surat_keluar,nomor_urut'],
        ]);

        $kategori = KategoriSurat::findOrFail($data['id_kategori_surat']);

        // Mode nomor urut: 'auto' pakai terbesar+1 (dihitung ULANG saat submit,
        // bukan pakai preview lama - mencegah dobel kalau 2 orang buat surat
        // bersamaan), atau 'manual' pakai angka yang diketik sendiri.
        $nomorUrut = $request->input('mode_nomor') === 'manual'
            ? (int) $request->input('nomor_urut_manual')
            : SuratKeluar::nomorUrutTerbesar() + 1;

        $susunan = SuratKeluar::susunKode($kategori, $nomorUrut, $data['tanggal_surat']);
        $data = array_merge($data, $susunan);

        if ($request->hasFile('lampiran')) {
            $data['lampiran'] = $request->file('lampiran')->store('surat-keluar', 'public');
        }

        $data['dibuat_oleh'] = Auth::guard('member')->id();

        SuratKeluar::create($data);

        return redirect()->route('surat-keluar.index')->with('status', 'Surat keluar berhasil dibuat dengan nomor '.$data['kode_surat'].'.');
    }

    public function edit(SuratKeluar $suratKeluar)
    {
        $daftarKategori = KategoriSurat::orderBy('nama')->get();

        return view('persuratan.surat-keluar.form', [
            'item' => $suratKeluar,
            'daftarKategori' => $daftarKategori,
            'nomorUrutBerikutnya' => null,
        ]);
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
            'id_kategori_surat' => ['required', 'exists:kategori_surat,id'],
            'tanggal_surat' => ['required', 'date'],
            'tujuan_surat' => ['required', 'string', 'max:150'],
            'perihal' => ['required', 'string', 'max:200'],
        ]);
    }
}
