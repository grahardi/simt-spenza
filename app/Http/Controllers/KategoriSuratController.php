<?php

namespace App\Http\Controllers;

use App\Models\KategoriSurat;
use App\Models\PengaturanSurat;
use Illuminate\Http\Request;

class KategoriSuratController extends Controller
{
    public function index()
    {
        $kategori = KategoriSurat::orderBy('kode')->get();
        $pengaturan = PengaturanSurat::ambil();

        return view('persuratan.kategori-surat.index', compact('kategori', 'pengaturan'));
    }

    public function create()
    {
        return view('persuratan.kategori-surat.form', ['item' => new KategoriSurat()]);
    }

    public function store(Request $request)
    {
        KategoriSurat::create($this->validated($request));

        return redirect()->route('kategori-surat.index')->with('status', 'Kategori surat berhasil ditambahkan.');
    }

    public function edit(KategoriSurat $kategoriSurat)
    {
        return view('persuratan.kategori-surat.form', ['item' => $kategoriSurat]);
    }

    public function update(Request $request, KategoriSurat $kategoriSurat)
    {
        $kategoriSurat->update($this->validated($request));

        return redirect()->route('kategori-surat.index')->with('status', 'Kategori surat berhasil diperbarui.');
    }

    public function destroy(KategoriSurat $kategoriSurat)
    {
        if ($kategoriSurat->suratKeluar()->exists()) {
            return back()->with('status', 'Kategori ini tidak bisa dihapus karena masih dipakai di surat keluar yang sudah ada.');
        }

        $kategoriSurat->delete();

        return redirect()->route('kategori-surat.index')->with('status', 'Kategori surat berhasil dihapus.');
    }

    /** Update kode baku (setting global, satu-satunya field yang bisa diubah di sini). */
    public function updatePengaturan(Request $request)
    {
        $data = $request->validate(['kode_baku' => ['required', 'string', 'max:50']]);

        PengaturanSurat::ambil()->update($data);

        return redirect()->route('kategori-surat.index')->with('status', 'Kode baku berhasil diperbarui.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20'],
            'nama' => ['required', 'string', 'max:100'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ]);
    }
}
