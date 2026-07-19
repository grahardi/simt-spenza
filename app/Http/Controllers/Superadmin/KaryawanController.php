<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Karyawan;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KaryawanController extends Controller
{
    /** CRUD Data Karyawan (staf non-guru, termasuk Tata Usaha). TIDAK ADA hapus - non-aktifkan saja. */
    public function index(Request $request)
    {
        $karyawan = Karyawan::query()
            ->when($request->filled('cari'), fn ($q) => $q->where('nama', 'like', '%'.$request->input('cari').'%'))
            ->when($request->input('status') === 'aktif', fn ($q) => $q->where('aktif', true))
            ->when($request->input('status') === 'nonaktif', fn ($q) => $q->where('aktif', false))
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.karyawan.index', compact('karyawan'));
    }

    public function create()
    {
        return view('superadmin.karyawan.form', ['karyawan' => new Karyawan()]);
    }

    public function store(Request $request)
    {
        Karyawan::create($this->validated($request) + ['aktif' => true]);

        return redirect()->route('superadmin.karyawan.index')->with('status', 'Karyawan baru berhasil ditambahkan.');
    }

    public function edit(Karyawan $karyawan)
    {
        return view('superadmin.karyawan.form', compact('karyawan'));
    }

    public function update(Request $request, Karyawan $karyawan)
    {
        $karyawan->update($this->validated($request));

        return redirect()->route('superadmin.karyawan.index')->with('status', 'Data karyawan berhasil diperbarui.');
    }

    public function toggleAktif(Karyawan $karyawan)
    {
        $karyawan->update(['aktif' => !$karyawan->aktif]);

        return back()->with('status', $karyawan->nama.' sekarang '.($karyawan->aktif ? 'AKTIF' : 'NONAKTIF').'.');
    }

    /** Halaman kelola akun login & roles karyawan ini. */
    public function roles(Karyawan $karyawan)
    {
        $member = Member::where('id_karyawan', $karyawan->id_karyawan)->first();

        return view('superadmin.karyawan.roles', compact('karyawan', 'member'));
    }

    public function buatAkun(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate([
            'user' => ['required', 'string', 'max:20', 'unique:member,user'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        Member::create([
            'id' => Member::idBerikutnya(),
            'user' => $data['user'],
            'password' => Hash::make($data['password']),
            'nama' => $karyawan->nama,
            'id_karyawan' => $karyawan->id_karyawan,
            'wajib_ganti_password' => true,
        ]);

        return redirect()->route('superadmin.karyawan.roles', $karyawan)->with('status', 'Akun login berhasil dibuat.');
    }

    public function simpanRoles(Request $request, Karyawan $karyawan)
    {
        $member = Member::where('id_karyawan', $karyawan->id_karyawan)->firstOrFail();

        $data = $request->validate([
            'jabatan' => ['nullable', 'string', 'max:20'],
            'piket' => ['nullable', 'string', 'max:20'],
        ]);

        $flagRoles = ['admin', 'tatib', 'bk', 'guru', 'keagamaan', 'kebersihan', 'kepsek', 'adminsoal', 'tata_usaha', 'uks', 'kesiswaan'];
        foreach ($flagRoles as $flag) {
            $data[$flag] = $request->boolean($flag) ? 1 : 0;
        }

        $member->update($data);

        return redirect()->route('superadmin.karyawan.roles', $karyawan)->with('status', 'Roles akun '.$karyawan->nama.' berhasil diperbarui.');
    }

    public function resetPassword(Request $request, Karyawan $karyawan)
    {
        $data = $request->validate(['password_baru' => ['required', 'string', 'min:6']]);

        $member = Member::where('id_karyawan', $karyawan->id_karyawan)->firstOrFail();
        $member->update(['password' => Hash::make($data['password_baru']), 'wajib_ganti_password' => true]);

        \App\Models\LogAktivitas::catat('password', 'Superadmin mereset password akun karyawan '.$karyawan->nama.'.');

        return back()->with('status', 'Password berhasil direset.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nip' => ['nullable', 'string', 'max:40'],
            'nama' => ['required', 'string', 'max:70'],
            'jenis_kelamin' => ['nullable', 'in:L,P'],
            'jabatan' => ['nullable', 'string', 'max:50'],
            'status' => ['nullable', 'string', 'max:40'],
            'alamat' => ['nullable', 'string', 'max:100'],
            'telepon' => ['nullable', 'string', 'max:50'],
            'tgl_lahir' => ['nullable', 'date'],
        ]);
    }
}
