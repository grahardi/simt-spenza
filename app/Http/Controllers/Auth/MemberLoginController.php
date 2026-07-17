<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class MemberLoginController extends Controller
{
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Pengganti masuk.php. Login pakai nomor ID (kolom `user`), bukan email -
     * sesuai kebiasaan akun yang sudah ada, supaya tidak perlu migrasi data akun.
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'user' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::guard('member')->attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'user' => 'Nomor ID atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        /** @var \App\Models\Member $member */
        $member = Auth::guard('member')->user();
        $member->update(['last_login_at' => now()]);
        \App\Models\LogAktivitas::catat('login', $member->nama.' ('.$member->user.') login ke sistem.');

        return redirect()->intended(route('dashboard'));
    }

    public function destroy(Request $request)
    {
        Auth::guard('member')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
