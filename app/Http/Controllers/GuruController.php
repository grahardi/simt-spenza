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

    /** Pengganti panel "Absen Guru" piket - list guru, link ke jadwal masing-masing (bukan CRUD). */
    /** List guru yang tercatat ABSEN hari ini saja (bukan semua guru) - klik buat lihat jadwal+tugas. */
    public function absenList(Request $request)
    {
        $hariIni = \App\Models\Member::namaHariJakartaHuruBesar();
        $tanggalHariIni = now('Asia/Jakarta')->toDateString();

        $absensiGuru = \App\Models\AbsensiGuru::with('guru')
            ->whereDate('tanggal', $tanggalHariIni)
            ->get()
            ->filter(fn ($a) => $a->guru !== null);

        $daftar = $absensiGuru->map(function ($absen) use ($hariIni, $tanggalHariIni) {
            $jadwalHariIni = \App\Models\DataJadwal::where('kodeguru', $absen->id_guru)
                ->where('hari', $hariIni)
                ->orderBy('jamhari')
                ->get();

            $tugasHariIni = \App\Models\Tugas::where('idguru', $absen->id_guru)
                ->whereDate('tgl_tugas', $tanggalHariIni)
                ->get()
                ->keyBy('kelas');

            return (object) [
                'absen' => $absen,
                'guru' => $absen->guru,
                'jadwal' => $jadwalHariIni,
                'tugas' => $tugasHariIni,
            ];
        })->values();

        return view('guru.absen-list', compact('daftar'));
    }

    public function create()
    {
        return view('guru.form', ['guru' => new Guru()]);
    }

    /** Manajemen WhatsApp - list anak wali + nomor WA terdaftar (Ayah/Ibu/Wali), link wa.me langsung. */
    public function manajemenWhatsapp()
    {
        $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        $siswa = \App\Models\Siswa::where('id_guru_wali', $guru->id_guru)
            ->with('nomorWhatsapp')
            ->orderBy('nama_lengkap')
            ->get();

        return view('guru.manajemen-whatsapp', compact('guru', 'siswa'));
    }

    /** Guru Wali - list siswa yang jadi anak wali guru yang sedang login. */
    public function waliSiswa()
    {
        $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        $siswa = \App\Models\Siswa::where('id_guru_wali', $guru->id_guru)
            ->orderBy('kelas')
            ->orderBy('nama_lengkap')
            ->get();

        return view('guru.wali-siswa', compact('guru', 'siswa'));
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
