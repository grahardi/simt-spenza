<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /** Pengganti daftarnama.php (list + cari). */
    public function index(Request $request)
    {
        $perPage = in_array((int) $request->input('per_page'), [10, 20, 50], true)
            ? (int) $request->input('per_page')
            : 10;

        $siswa = Siswa::query()
            ->when($request->filled('cari'), function ($query) use ($request) {
                $query->where('nama_lengkap', 'like', '%'.$request->input('cari').'%');
            })
            ->when($request->filled('kelas'), function ($query) use ($request) {
                $query->where('kelas', $request->input('kelas'));
            })
            ->orderBy('kelas')
            ->orderBy('nama_lengkap')
            ->paginate($perPage)
            ->withQueryString();

        $daftarKelas = Siswa::query()
            ->select('kelas')
            ->distinct()
            ->orderBy('kelas')
            ->pluck('kelas');

        return view('siswa.index', compact('siswa', 'daftarKelas', 'perPage'));
    }

    /** Cetak daftar siswa 1 kelas ke PDF. */
    public function printKelas(Request $request)
    {
        $kelas = $request->input('kelas');
        abort_if(!$kelas, 400, 'Pilih kelas dulu sebelum mencetak.');

        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap')->get();

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('siswa.print-pdf', compact('siswa', 'kelas'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('Daftar Siswa Kelas '.$kelas.'.pdf');
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
            'nomer_bangku' => ['nullable', 'integer'],
        ]);
    }
}
