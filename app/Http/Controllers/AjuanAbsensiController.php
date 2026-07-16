<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\AjuanAbsensi;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanAbsensiController extends Controller
{
    /**
     * Pengganti ajuanabsenpilih.php/laporentry.php - form Admin Absensi
     * cari siswa lalu ajukan absensi (BELUM masuk ke absen_siswa).
     */
    public function ajukan(Request $request)
    {
        $siswa = null;
        $cari = trim((string) $request->input('cari'));
        $hariIni = Carbon::today();

        if ($cari !== '') {
            $siswa = Siswa::query()
                ->where(function ($query) use ($cari) {
                    $query->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('id_member', 'like', '%'.$cari.'%');
                })
                ->orderByDesc('id_member')
                ->limit(20)
                ->get();

            $ajuanHariIni = AjuanAbsensi::whereIn('id_siswa', $siswa->pluck('id_member'))
                ->whereDate('tgl_absen', $hariIni)
                ->get()
                ->keyBy('id_siswa');

            $siswa->each(function ($s) use ($ajuanHariIni) {
                $s->ajuanHariIni = $ajuanHariIni->get($s->id_member);
            });
        }

        return view('ajuan-absensi.ajukan', ['siswa' => $siswa, 'cari' => $cari]);
    }

    /**
     * Pengganti prosesajuan.php - simpan ajuan (belum masuk absensi resmi).
     */
    public function simpan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'keterangan' => ['required', 'in:s,i,d'],
            'catatan' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $atribut = [
            'keterangan' => $data['keterangan'],
            'tambahan' => $data['catatan'] ?? null,
            'id_entry' => Auth::guard('member')->id(),
        ];

        if ($request->hasFile('foto')) {
            $atribut['gambar'] = $request->file('foto')->store('ajuan-absensi', 'public');
        }

        AjuanAbsensi::updateOrCreate(
            ['id_siswa' => $siswa->id_member, 'tgl_absen' => Carbon::today()->toDateString()],
            $atribut
        );

        return back()->with('status', 'Ajuan absensi '.$siswa->nama_lengkap.' berhasil dikirim, menunggu ACC piket.');
    }

    /**
     * Pengganti ajuanabsenedit.php/verifikasi.php - antrean ACC untuk piket,
     * dengan filter tanggal (default hari ini).
     */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $ajuan = AjuanAbsensi::with(['siswa', 'diajukanOleh'])
            ->whereDate('tgl_absen', $tanggal)
            ->orderByDesc('id_absen_siswa')
            ->paginate(20)
            ->withQueryString();

        return view('ajuan-absensi.index', compact('ajuan', 'tanggal'));
    }

    /**
     * ACC: pindahkan ajuan ke absen_siswa (baru resmi tercatat absen),
     * lalu hapus dari antrean ajuan.
     */
    public function acc(AjuanAbsensi $ajuan)
    {
        AbsenSiswa::updateOrCreate(
            ['id_siswa' => $ajuan->id_siswa, 'tgl_absen' => $ajuan->tgl_absen],
            ['keterangan' => $ajuan->keterangan, 'tambahan' => $ajuan->tambahan, 'gambar' => $ajuan->gambar]
        );

        $nama = $ajuan->siswa->nama_lengkap ?? 'siswa';
        $ajuan->delete();

        return back()->with('status', 'Ajuan '.$nama.' disetujui dan sudah masuk absensi resmi.');
    }

    /** Tolak: hapus ajuan tanpa masuk absensi (siswa tetap dianggap belum absen / berpotensi alpha). */
    public function tolak(AjuanAbsensi $ajuan)
    {
        $nama = $ajuan->siswa->nama_lengkap ?? 'siswa';
        $ajuan->delete();

        return back()->with('status', 'Ajuan '.$nama.' ditolak.');
    }
}
