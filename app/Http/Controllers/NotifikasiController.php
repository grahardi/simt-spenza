<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Warning;
use Illuminate\Support\Facades\Auth;

class NotifikasiController extends Controller
{
    /**
     * Pengganti notif.php - guru lihat semua warning/ajuan yang ditujukan
     * ke dirinya, termasuk yang belum ditanggapi (aksi = 'Belum').
     */
    public function index()
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();

        $notifikasi = collect();

        if ($member->id_guru) {
            $notifikasi = Warning::with('pelapor')
                ->where('id_guru', $member->id_guru)
                ->orderByDesc('id')
                ->limit(50)
                ->get();
        }

        return view('notifikasi.index', compact('notifikasi'));
    }
}
