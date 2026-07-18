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

    /** Export nomor guru jadi file vCard (.vcf), format nama "Guru {nama}". */
    public function exportVcf()
    {
        $semua = GuruWhatsapp::with('guru')->get();

        $vcf = '';
        foreach ($semua as $n) {
            if (!$n->guru) {
                continue;
            }
            $vcf .= "BEGIN:VCARD\r\nVERSION:3.0\r\nFN:Guru {$n->guru->nama}\r\nTEL;TYPE=CELL:+{$n->nomor}\r\nEND:VCARD\r\n";
        }

        return response($vcf, 200, [
            'Content-Type' => 'text/vcard',
            'Content-Disposition' => 'attachment; filename="kontak-guru.vcf"',
        ]);
    }
}
