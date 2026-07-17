<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Karena situs sekarang publik, akun yang wajib_ganti_password = true
 * (password masih plain text saat migrasi, atau belum pernah login)
 * dipaksa ganti password dulu sebelum bisa akses halaman lain manapun.
 */
class ForcePasswordChange
{
    public function handle(Request $request, Closure $next): Response
    {
        $member = Auth::guard('member')->user();

        $rutePengecualian = ['ganti-password', 'ganti-password.simpan', 'logout'];

        if ($member && $member->wajib_ganti_password && !$request->routeIs($rutePengecualian)) {
            return redirect()->route('ganti-password');
        }

        return $next($request);
    }
}
