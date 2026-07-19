<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    /** CRUD Data Guru superadmin - Tambah/Edit/Nonaktif. TIDAK ADA hapus (data historis terkait). */
    public function index(Request $request)
    {
        $guru = Guru::query()
            ->when($request->filled('cari'), fn ($q) => $q->where('nama', 'like', '%'.$request->input('cari').'%'))
            ->when($request->input('status') === 'aktif', fn ($q) => $q->where('aktif', true))
            ->when($request->input('status') === 'nonaktif', fn ($q) => $q->where('aktif', false))
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.guru.index', compact('guru'));
    }

    public function create()
    {
        return view('superadmin.guru.form', ['guru' => new Guru()]);
    }

    public function store(Request $request)
    {
        Guru::create($this->validated($request) + ['aktif' => true]);

        return redirect()->route('superadmin.guru.index')->with('status', 'Guru baru berhasil ditambahkan.');
    }

    public function edit(Guru $guru)
    {
        return view('superadmin.guru.form', compact('guru'));
    }

    public function update(Request $request, Guru $guru)
    {
        $guru->update($this->validated($request));

        return redirect()->route('superadmin.guru.index')->with('status', 'Data guru berhasil diperbarui.');
    }

    /** Nonaktifkan/aktifkan lagi - bukan hapus, supaya jadwal/absensi/dll yang mengacu ke guru ini tetap utuh. */
    public function toggleAktif(Guru $guru)
    {
        $guru->update(['aktif' => !$guru->aktif]);

        return back()->with('status', 'Guru '.$guru->nama.' sekarang '.($guru->aktif ? 'AKTIF' : 'NONAKTIF').'.');
    }

    /** Halaman kelola akun login & roles guru ini. */
    public function roles(Guru $guru)
    {
        $member = Member::where('id_guru', $guru->id_guru)->first();

        return view('superadmin.guru.roles', compact('guru', 'member'));
    }

    /** Buat akun login baru untuk guru ini (kalau belum ada). */
    public function buatAkun(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'user' => ['required', 'string', 'max:20', 'unique:member,user'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        Member::create([
            'id' => Member::idBerikutnya(),
            'user' => $data['user'],
            'password' => Hash::make($data['password']),
            'nama' => $guru->nama,
            'id_guru' => $guru->id_guru,
            'wajib_ganti_password' => true,
        ]);

        return redirect()->route('superadmin.guru.roles', $guru)->with('status', 'Akun login berhasil dibuat.');
    }

    /** Simpan perubahan roles (flag admin/walikelas/tatib/dll) + jabatan untuk akun guru ini. */
    public function simpanRoles(Request $request, Guru $guru)
    {
        $member = Member::where('id_guru', $guru->id_guru)->firstOrFail();

        $data = $request->validate([
            'jabatan' => ['nullable', 'string', 'max:20'],
            'walikelas' => ['nullable', 'string', 'max:10'],
            'piket' => ['nullable', 'string', 'max:20'],
        ]);

        $flagRoles = ['admin', 'tatib', 'bk', 'guru', 'keagamaan', 'kebersihan', 'kepsek', 'adminsoal', 'tata_usaha', 'uks'];
        foreach ($flagRoles as $flag) {
            $data[$flag] = $request->boolean($flag) ? 1 : 0;
        }

        $member->update($data);

        return redirect()->route('superadmin.guru.roles', $guru)->with('status', 'Roles akun '.$guru->nama.' berhasil diperbarui.');
    }

    /** Reset password akun guru ini. */
    public function resetPassword(Request $request, Guru $guru)
    {
        $data = $request->validate(['password_baru' => ['required', 'string', 'min:6']]);

        $member = Member::where('id_guru', $guru->id_guru)->firstOrFail();
        $member->update(['password' => Hash::make($data['password_baru']), 'wajib_ganti_password' => true]);

        return back()->with('status', 'Password berhasil direset.');
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
