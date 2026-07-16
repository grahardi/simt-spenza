<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use Illuminate\Http\Request;

class GuruController extends Controller
{
    public function index(Request $request)
    {
        $guru = Guru::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama', 'like', '%'.$request->input('cari').'%');
            })
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('guru.index', compact('guru'));
    }

    public function create()
    {
        return view('guru.form', ['guru' => new Guru()]);
    }

    public function store(Request $request)
    {
        Guru::create($this->validated($request));

        return redirect()->route('guru.index')->with('status', 'Data guru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('guru.form', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $guru->update($this->validated($request));

        return redirect()->route('guru.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    public function destroy(Guru $guru)
    {
        $guru->delete();

        return redirect()->route('guru.index')->with('status', 'Data guru berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nip' => ['nullable', 'string', 'max:40'],
            'nuptk' => ['nullable', 'string', 'max:40'],
            'nama' => ['required', 'string', 'max:70'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'status' => ['nullable', 'string', 'max:40'],
            'alamat' => ['nullable', 'string', 'max:50'],
            'jabatan' => ['nullable', 'string', 'max:20'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'tgl_lahir' => ['nullable', 'date'],
        ]);
    }
}
