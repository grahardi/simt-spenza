<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use Illuminate\Support\Facades\Auth;

class WalikelasWhatsappController extends Controller
{
    public function index()
    {
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        abort_if($kelas === '', 403, 'Akun ini tidak terhubung ke kelas manapun sebagai wali kelas.');

        $siswa = Siswa::where('kelas', $kelas)
            ->with('nomorWhatsapp')
            ->orderBy('nama_lengkap')
            ->get();

        return view('walikelas.manajemen-whatsapp', compact('kelas', 'siswa'));
    }
}
