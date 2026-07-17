<?php

namespace App\Http\Controllers;

use App\Models\DataJadwal;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JadwalGuruController extends Controller
{
    /**
     * Pengganti jadwalguru.php - jadwal mengajar guru pada HARI INI
     * (waktu Jakarta), diurutkan per jam pelajaran.
     */
    public function index(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $idGuru = $member->id_guru;

        $hari = Member::namaHariJakartaHuruBesar();

        $jadwal = DataJadwal::where('kodeguru', $idGuru)
            ->where('hari', $hari)
            ->orderBy('jamhari')
            ->get();

        $sekarang = \Carbon\Carbon::now('Asia/Jakarta')->format('H.i');

        return view('jadwal.guru', [
            'jadwal' => $jadwal,
            'hari' => $hari,
            'guru' => $member->guru,
            'sekarang' => $sekarang,
        ]);
    }
}
