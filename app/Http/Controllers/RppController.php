<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Rpp;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RppController extends Controller
{
    /** Pengganti bagian upload di uploadrpp.php - form upload RPP guru sendiri. */
    public function upload()
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();

        $daftar = Rpp::where('id_guru', $member->id_guru)->orderByDesc('tanggal')->get();

        return view('rpp.upload', compact('daftar'));
    }

    /** Pengganti uploadrpp.php - simpan file RPP (PDF). */
    public function simpan(Request $request)
    {
        $data = $request->validate([
            'bulan' => ['required', 'string', 'max:20'],
            'file' => ['required', 'file', 'mimes:pdf', 'max:5120'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        $path = $request->file('file')->store('rpp', 'public');

        Rpp::create([
            'id_guru' => $member->id_guru,
            'bulan' => $data['bulan'],
            'tanggal' => Carbon::today()->toDateString(),
            'namafile' => $path,
            'status' => '1',
        ]);

        return back()->with('status', 'RPP bulan '.$data['bulan'].' berhasil diupload, menunggu persetujuan kepala sekolah.');
    }

    /** Pengganti rppall.php - daftar semua RPP untuk kepala sekolah, dengan tombol setujui. */
    public function semua(Request $request)
    {
        $rpp = Rpp::with('guru')
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        return view('rpp.semua', compact('rpp'));
    }

    /**
     * Pengganti setujuirpp.php - setujui 1 file RPP.
     * Perbaikan dari lama: menyetujui SATU file, bukan semua file guru itu
     * sekaligus (supaya kepsek bisa menyetujui per bulan/per file).
     */
    public function setujui(Rpp $rppItem)
    {
        $rppItem->update(['status' => '2']);

        return back()->with('status', 'RPP '.($rppItem->guru->nama ?? '').' bulan '.$rppItem->bulan.' disetujui.');
    }
}
