<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\GuruWhatsapp;
use Illuminate\Http\Request;

class WhatsappGuruController extends Controller
{
    /** Daftar nomor WA yang terhubung ke guru (hasil registrasi lewat fitur tersembunyi 'regis-guru'). */
    public function index(Request $request)
    {
        $nomor = GuruWhatsapp::with('guru')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where('nomor', 'like', '%'.$cari.'%')
                    ->orWhereHas('guru', fn ($qq) => $qq->where('nama', 'like', '%'.$cari.'%'));
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.whatsapp-guru.index', compact('nomor'));
    }

    public function putuskan(GuruWhatsapp $guruWhatsapp)
    {
        $nama = $guruWhatsapp->guru->nama ?? 'guru';
        $guruWhatsapp->delete();

        return back()->with('status', 'Nomor WhatsApp berhasil diputuskan dari '.$nama.'.');
    }
}
