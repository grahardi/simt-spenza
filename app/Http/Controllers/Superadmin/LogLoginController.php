<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class LogLoginController extends Controller
{
    /** Menu terpisah khusus riwayat login (beda dari Log Aktivitas umum). */
    public function index(Request $request)
    {
        $log = LogAktivitas::with('member')
            ->where('kategori', 'login')
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.log-login.index', compact('log'));
    }
}
