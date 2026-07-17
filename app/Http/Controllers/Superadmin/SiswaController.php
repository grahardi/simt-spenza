<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * CRUD Data Siswa superadmin - SENGAJA tidak ada method destroy().
     * Menghapus data siswa berisiko merusak riwayat absensi/pelanggaran/
     * bimbingan yang mengacu ke id_member itu. Kalau siswa keluar/pindah,
     * pakai fitur Mutasi, bukan hapus.
     */
    public function index(Request $request)
    {
        $siswa = Siswa::query()
            ->when($request->filled('cari'), fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->input('cari').'%'))
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->input('kelas')))
            ->orderBy('kelas')->orderBy('nama_lengkap')
            ->paginate(20)
            ->withQueryString();

        $daftarKelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('superadmin.siswa.index', compact('siswa', 'daftarKelas'));
    }

    public function create()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('superadmin.siswa.form', ['siswa' => new Siswa(), 'daftarKelas' => $daftarKelas]);
    }

    public function store(Request $request)
    {
        Siswa::create($this->validated($request));

        return redirect()->route('superadmin.siswa.index')->with('status', 'Siswa baru berhasil ditambahkan.');
    }

    public function edit(Siswa $siswa)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('superadmin.siswa.form', compact('siswa', 'daftarKelas'));
    }

    public function update(Request $request, Siswa $siswa)
    {
        $siswa->update($this->validated($request));

        return redirect()->route('superadmin.siswa.index')->with('status', 'Data siswa berhasil diperbarui.');
    }

    /** Mutasi - pindah kelas (atau tandai keluar/lulus lewat kelas="OUT"), terpisah dari edit biodata biasa. */
    public function mutasiForm(Siswa $siswa)
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('superadmin.siswa.mutasi', compact('siswa', 'daftarKelas'));
    }

    public function mutasi(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'kelas_baru' => ['required', 'string', 'max:20'],
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        $kelasLama = $siswa->kelas;
        $siswa->update(['kelas' => $data['kelas_baru']]);

        return redirect()->route('superadmin.siswa.index')
            ->with('status', "Mutasi berhasil: {$siswa->nama_lengkap} dari kelas {$kelasLama} ke {$data['kelas_baru']}.");
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nisn' => ['nullable', 'string', 'max:50'],
            'kelas' => ['required', 'string', 'max:20'],
            'nama_lengkap' => ['required', 'string', 'max:50'],
            'jenis_kelamin' => ['required', 'in:L,P'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'max:100'],
            'whatsapp' => ['nullable', 'string', 'max:20'],
            'nomer_bangku' => ['nullable', 'integer'],
        ]);
    }
}
