<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappLog;
use Illuminate\Http\Request;

class WhatsappLogController extends Controller
{
    public function index(Request $request)
    {
        $arah = $request->input('arah', 'keluar');

        $log = WhatsappLog::where('arah', $arah)
            ->when($request->filled('nomor'), fn ($q) => $q->where('nomor', 'like', '%'.$request->input('nomor').'%'))
            ->when($request->filled('status') && $arah === 'keluar', function ($q) use ($request) {
                if ($request->input('status') === 'berhasil') {
                    $q->where('berhasil', true);
                } elseif ($request->input('status') === 'gagal') {
                    $q->where('berhasil', false);
                }
            })
            ->orderByDesc('created_at')
            ->paginate(30)
            ->withQueryString();

        return view('superadmin.whatsapp-log.index', compact('log', 'arah'));
    }
}
