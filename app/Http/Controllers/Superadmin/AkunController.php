<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AkunController extends Controller
{
    /**
     * Kelola SEMUA akun login (tabel member) - beda dari halaman "Roles"
     * di Data Guru yang cuma bisa diakses lewat guru yang sudah terdaftar.
     * Ada akun (misal piket/admin murni) yang tidak terhubung ke data guru
     * manapun, jadi butuh jalan masuk sendiri di sini.
     */
    public function index(Request $request)
    {
        $akun = Member::with('guru')
            ->when($request->filled('cari'), fn ($q) => $q->where('nama', 'like', '%'.$request->input('cari').'%')->orWhere('user', 'like', '%'.$request->input('cari').'%'))
            ->orderBy('nama')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.akun.index', compact('akun'));
    }

    /**
     * "Login As" - masuk sebagai akun ini tanpa perlu tahu password-nya.
     * ID superadmin asli disimpan di session supaya bisa kembali lagi nanti.
     */
    public function loginSebagai(Member $akun)
    {
        $superadminAsli = \Illuminate\Support\Facades\Auth::guard('member')->id();

        if ($akun->id === $superadminAsli) {
            return back()->with('status', 'Ini akun Anda sendiri.');
        }

        session(['impersonator_id' => $superadminAsli]);
        \Illuminate\Support\Facades\Auth::guard('member')->login($akun);

        return redirect()->route('dashboard')->with('status', 'Sekarang login sebagai '.$akun->nama.'. Klik "Kembali ke Superadmin" di pojok atas kapan saja untuk keluar.');
    }

    /** Kembali ke akun superadmin asli setelah selesai "Login As". */
    public function kembaliKeSuperadmin()
    {
        $superadminAsli = session('impersonator_id');

        if (!$superadminAsli) {
            return redirect()->route('dashboard');
        }

        session()->forget('impersonator_id');
        \Illuminate\Support\Facades\Auth::guard('member')->loginUsingId($superadminAsli);

        return redirect()->route('superadmin.akun.index')->with('status', 'Kembali ke akun Superadmin.');
    }

    public function create()
    {
        $daftarGuru = Guru::orderBy('nama')->get();
        $daftarKaryawan = \App\Models\Karyawan::orderBy('nama')->get();

        return view('superadmin.akun.form', compact('daftarGuru', 'daftarKaryawan') + ['member' => new Member()]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user' => ['required', 'string', 'max:20', 'unique:member,user'],
            'password' => ['required', 'string', 'min:6'],
            'nama' => ['required', 'string', 'max:70'],
            'id_guru' => ['nullable', 'integer'],
            'id_karyawan' => ['nullable', 'integer'],
        ]);

        $data['id'] = Member::idBerikutnya();
        $data['password'] = Hash::make($data['password']);
        $data['wajib_ganti_password'] = true;

        Member::create($data);

        \App\Models\LogAktivitas::catat('sistem', 'Superadmin membuat akun baru: '.$data['nama'].' ('.$data['user'].').');

        return redirect()->route('superadmin.akun.index')->with('status', 'Akun baru berhasil dibuat.');
    }

    public function edit(Member $akun)
    {
        return view('superadmin.akun.roles', ['member' => $akun]);
    }

    /** Simpan roles untuk akun ini (sama logikanya dengan GuruController::simpanRoles, tapi tanpa syarat harus ada guru). */
    public function update(Request $request, Member $akun)
    {
        $data = $request->validate([
            'jabatan' => ['nullable', 'string', 'max:20'],
            'walikelas' => ['nullable', 'string', 'max:10'],
            'piket' => ['nullable', 'string', 'max:20'],
        ]);

        $flagRoles = ['admin', 'tatib', 'bk', 'guru', 'keagamaan', 'kebersihan', 'kepsek', 'adminsoal', 'tata_usaha', 'uks'];
        foreach ($flagRoles as $flag) {
            $data[$flag] = $request->boolean($flag) ? 1 : 0;
        }

        $akun->update($data);

        return redirect()->route('superadmin.akun.index')->with('status', 'Roles akun '.$akun->nama.' berhasil diperbarui.');
    }

    public function resetPassword(Request $request, Member $akun)
    {
        $data = $request->validate(['password_baru' => ['required', 'string', 'min:6']]);

        $akun->update(['password' => Hash::make($data['password_baru']), 'wajib_ganti_password' => true]);

        \App\Models\LogAktivitas::catat('password', 'Superadmin mereset password akun '.$akun->nama.' ('.$akun->user.').');

        return back()->with('status', 'Password berhasil direset.');
    }
}
