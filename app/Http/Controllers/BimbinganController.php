<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\Member;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganController extends Controller
{
    /** Cari siswa sebelum entry bimbingan - pengganti pola pencarian bimbinganentry.php. */
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

        return view('bimbingan.cari', ['siswa' => $siswa, 'cari' => $cari]);
    }

    /** Pengganti bimbinganentry.php - form entry bimbingan untuk 1 siswa. */
    public function lapor(Siswa $siswa)
    {
        return view('bimbingan.lapor', compact('siswa'));
    }

    /** Pengganti prosesbk.php - simpan hasil bimbingan + foto. */
    public function simpan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'kategori' => ['required', 'in:Pendampingan,Verifikasi,Pelanggaran,Lainnya'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'tindakan' => ['required', 'in:Tidak Ada,Notifikasi,Peringatan,Tindakan'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        $atribut = [
            'id_siswa' => $siswa->id_member,
            'tgl_bimbingan' => Carbon::today()->toDateString(),
            'kategori' => $data['kategori'],
            'Keterangan' => $data['keterangan'] ?? null,
            'Tindakan' => $data['tindakan'],
            'id_entry' => $member->id,
            'guru_bk' => $member->id_guru,
        ];

        if ($request->hasFile('foto')) {
            $atribut['gambar'] = $request->file('foto')->store('bimbingan', 'public');
        }

        Bimbingan::create($atribut);

        return redirect()->route('bimbingan.index')->with('status', 'Catatan bimbingan '.$siswa->nama_lengkap.' berhasil disimpan.');
    }

    /** Pengganti bimbinganlist.php - daftar semua catatan bimbingan, filter tanggal. */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $bimbingan = Bimbingan::with(['siswa', 'pelapor'])
            ->whereDate('tgl_bimbingan', $tanggal)
            ->orderByDesc('id_bk')
            ->paginate(20)
            ->withQueryString();

        return view('bimbingan.index', compact('bimbingan', 'tanggal'));
    }
}
