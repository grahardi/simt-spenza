<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\DataJadwal;
use App\Models\LaporKeagamaan;
use App\Models\Member;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KeagamaanController extends Controller
{
    /**
     * Pengganti laporagama.php - guru yang punya jadwal jam sholat (jamhari='x')
     * hari ini lihat daftar siswa di kelas yang diampu saat itu, untuk
     * dilaporkan Halangan/Bolos/Ijin (kecuali sudah tercatat absen resmi).
     */
    public function index(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $hari = Member::namaHariJakartaHuruBesar();
        $hariIni = Carbon::today();

        $kelasList = DataJadwal::where('kodeguru', $member->id_guru)
            ->where('hari', $hari)
            ->where('jamhari', 'x')
            ->pluck('kelas');

        $siswa = Siswa::whereIn('kelas', $kelasList)
            ->orderBy('kelas')->orderBy('nama_lengkap')
            ->get();

        $absenHariIni = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $hariIni)
            ->get()->keyBy('id_siswa');

        $laporHariIni = LaporKeagamaan::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_kegiatan', $hariIni)
            ->get()->keyBy('id_siswa');

        $siswa->each(function ($s) use ($absenHariIni, $laporHariIni) {
            $s->absenHariIni = $absenHariIni->get($s->id_member);
            $s->laporHariIni = $laporHariIni->get($s->id_member);
        });

        return view('keagamaan.index', ['siswa' => $siswa, 'kelasList' => $kelasList]);
    }

    /** Pengganti keagamaan.php - simpan laporan kegiatan keagamaan. */
    public function simpan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'pelanggaran' => ['required', 'in:halangan,membolos,ijin'],
            'keterangan' => ['nullable', 'string', 'max:50'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        LaporKeagamaan::updateOrCreate(
            ['id_siswa' => $siswa->id_member, 'tgl_kegiatan' => Carbon::today()->toDateString()],
            ['pelanggaran' => $data['pelanggaran'], 'keterangan' => $data['keterangan'] ?? null, 'id_entry' => $member->id]
        );

        return back()->with('status', 'Laporan keagamaan '.$siswa->nama_lengkap.' berhasil disimpan.');
    }

    /** Pengganti agamalanggarlist.php/listlaporanagama.php - rekap semua laporan, filter tanggal. */
    public function rekap(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $lapor = LaporKeagamaan::with(['siswa', 'pelapor'])
            ->whereDate('tgl_kegiatan', $tanggal)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('keagamaan.rekap', compact('lapor', 'tanggal'));
    }
}
