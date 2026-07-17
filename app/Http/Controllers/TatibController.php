<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Pelanggaran;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TatibController extends Controller
{
    /** Pengganti pola cari siswa di tatibentry.php - cari dulu sebelum lapor. */
    public function cari(Request $request)
    {
        $cari = trim((string) $request->input('cari'));
        $siswa = null;

        if ($cari !== '') {
            $siswa = Siswa::query()
                ->where(function ($query) use ($cari) {
                    $query->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('id_member', 'like', '%'.$cari.'%');
                })
                ->orderByDesc('id_member')
                ->limit(20)
                ->get();
        }

        return view('tatib.cari', ['siswa' => $siswa, 'cari' => $cari]);
    }

    /** Pengganti tatibentry.php - form lapor pelanggaran untuk 1 siswa. */
    public function lapor(Siswa $siswa)
    {
        return view('tatib.lapor', compact('siswa'));
    }

    /** Pengganti prosestatib.php - simpan laporan pelanggaran. */
    public function simpan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'kategori' => ['required', 'in:Peringatan,Ringan,Sedang,Berat'],
            'poin' => ['required', 'numeric'],
            'keterangan' => ['nullable', 'string', 'max:200'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        Pelanggaran::create([
            'id_siswa' => $siswa->id_member,
            'tgl_pelanggaran' => Carbon::today()->toDateString(),
            'kategori' => $data['kategori'],
            'keterangan' => $data['keterangan'] ?? null,
            'poin' => $data['poin'],
            'penanganan' => 'Belum',
            'id_entry' => $member->id,
        ]);

        return redirect()->route('tatib.index')->with('status', 'Laporan pelanggaran '.$siswa->nama_lengkap.' berhasil disimpan.');
    }

    /** Pengganti tatiblist.php/listpelanggar.php - daftar semua pelanggaran, filter tanggal. */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $pelanggaran = Pelanggaran::with(['siswa', 'pelapor'])
            ->whereDate('tgl_pelanggaran', $tanggal)
            ->orderByDesc('id_langgar')
            ->paginate(20)
            ->withQueryString();

        return view('tatib.index', compact('pelanggaran', 'tanggal'));
    }

    /** Pengganti updatetatib.php - tandai pelanggaran sudah ditangani. */
    public function tindak(Request $request, Pelanggaran $pelanggaran)
    {
        $data = $request->validate([
            'penanganan' => ['required', 'string', 'max:50'],
        ]);

        $pelanggaran->update([
            'penanganan' => $data['penanganan'],
            'tgl_action' => Carbon::today()->toDateString(),
        ]);

        return back()->with('status', 'Penanganan pelanggaran '.($pelanggaran->siswa->nama_lengkap ?? '').' berhasil disimpan.');
    }
}
