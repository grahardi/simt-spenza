<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;

class WhatsappNomorController extends Controller
{
    /** Daftar siswa yang nomor WA-nya sudah terhubung (hasil registrasi lewat bot atau input manual). */
    public function index(Request $request)
    {
        $siswa = Siswa::whereNotNull('whatsapp')
            ->where('whatsapp', '!=', '')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where(function ($qq) use ($cari) {
                    $qq->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('whatsapp', 'like', '%'.$cari.'%');
                });
            })
            ->orderBy('nama_lengkap')
            ->paginate(20)
            ->withQueryString();

        return view('superadmin.whatsapp-nomor.index', compact('siswa'));
    }

    /** Putuskan (unlink) nomor WA dari siswa - kalau salah pasang, bukan hapus siswanya. */
    public function putuskan(Siswa $siswa)
    {
        $siswa->update(['whatsapp' => null]);

        return back()->with('status', 'Nomor WhatsApp berhasil diputuskan dari '.$siswa->nama_lengkap.'.');
    }
}
