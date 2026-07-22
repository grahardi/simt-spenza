<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfilController extends Controller
{
    public function edit()
    {
        $member = Auth::guard('member')->user();

        return view('profil', compact('member'));
    }

    public function update(Request $request)
    {
        $member = Auth::guard('member')->user();

        $data = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'nip' => ['nullable', 'string', 'max:30'],
            'pangkat' => ['nullable', 'string', 'max:100'],
            'jabatan_dinas' => ['nullable', 'string', 'max:100'],
            'password_baru' => ['nullable', 'string', 'min:6', 'confirmed'],
        ]);

        $member->update([
            'nama' => $data['nama'],
            'pangkat' => $data['pangkat'] ?? null,
            'jabatan_dinas' => $data['jabatan_dinas'] ?? null,
        ]);

        // NIP tersimpan di tabel guru (bukan member) - update di situ kalau akun ini terhubung ke data guru
        if ($member->dataGuru) {
            $member->dataGuru->update(['nip' => $data['nip'] ?? null]);
        }

        if (!empty($data['password_baru'])) {
            $member->update(['password' => Hash::make($data['password_baru'])]);
            \App\Models\LogAktivitas::catat('password', $member->nama.' ('.$member->user.') mengganti password sendiri lewat halaman Profil.');
        }

        return redirect()->route('profil')->with('status', 'Profil berhasil diperbarui.');
    }
}
