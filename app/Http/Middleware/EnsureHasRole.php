<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Pengganti pola panel_guru.php, panel_walikelas.php, panel_tatib.php dkk
 * di sistem lama. Dipakai sebagai: ->middleware('role:guru,walikelas')
 * (boleh lebih dari satu role, dipisah koma - akses diberikan kalau
 * user punya salah satu dari role tersebut).
 */
class EnsureHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $member = Auth::guard('member')->user();

        if (!$member) {
            abort(403, 'Belum login.');
        }

        foreach ($roles as $role) {
            if ($member->hasRole($role)) {
                return $next($request);
            }
        }

        abort(403, 'Anda tidak punya akses ke halaman ini.');
    }
}
