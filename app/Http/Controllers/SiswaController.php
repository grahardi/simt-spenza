<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /** Pengganti daftarnama.php (list + cari). */
    public function index(Request $request)
    {
        $siswa = Siswa::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%'.$request->input('cari').'%');
            })
            ->when($request->filled('kelas'), function ($query) use ($request) {
                $query->where('kelas', $request->input('kelas'));
            })
            ->orderBy('kelas')
            ->orderBy('nama_lengkap')
            ->paginate(20)
            ->withQueryString();

        $daftarKelas = Siswa::query()
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        return view('siswa.index', compact('siswa', 'daftarKelas'));
    }

    /** Pengganti form tambah di siswatambah.php. */
    public function create()
    {
        return view('siswa.form', ['siswa' => new Siswa()]);
    }

    /** Pengganti proses simpan di prosessiswa.php - sudah divalidasi & pakai Eloquent (aman SQLi). */
    public function store(Request $request)
    {
        $data = $this->validated($request);

        Siswa::create($data);

        return redirect()->route('siswa.index')->with('status', 'Data siswa berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        return view('siswa.form', compact('siswa'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($this->validated($request));

        return redirect()->route('siswa.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    public function destroy(Siswa $siswa)
    {
        $siswa->delete();

        return redirect()->route('siswa.index')->with('status', 'Data siswa berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nisn' => ['nullable', 'string', 'max:50'],
            'kelas' => ['required', 'string', 'max:10'],
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'nomer_bangku' => ['nullable', 'integer'],
        ]);
    }
}
