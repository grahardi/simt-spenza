<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class GantiPasswordController extends Controller
{
    public function form()
    {
        $member = Auth::guard('member')->user();

        return view('auth.ganti-password', ['wajib' => $member->wajib_ganti_password]);
    }

    public function simpan(Request $request)
    {
        $member = Auth::guard('member')->user();

        $data = $request->validate([
            'password_baru' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $member->update([
            'password' => Hash::make($data['password_baru']),
            'wajib_ganti_password' => false,
        ]);

        return redirect()->route('dashboard')->with('status', 'Password berhasil diganti.');
    }
}
