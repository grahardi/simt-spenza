<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Member;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AktivitasKelasController extends Controller
{
    /**
     * Aktivitas kelas untuk wali kelas - rekap absensi hari ini untuk siswa
     * di kelas yang diampu. Kolom `member.walikelas` di sistem lama berisi
     * NAMA KELAS langsung (bukan flag 0/1), contoh: "7 - A" - dipakai untuk
     * mencocokkan siswa di kelas yang sama (lihat home.php/kebersihan.php lama).
     */
    public function index(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $siswa = Siswa::where('kelas', $kelas)
            ->orderBy('nama_lengkap')
            ->get();

        $absen = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $tanggal)
            ->get()
            ->keyBy('id_siswa');

        $siswa->each(function ($s) use ($absen) {
            $s->absenHariIni = $absen->get($s->id_member);
        });

        $rekap = [
            'hadir' => $siswa->filter(fn ($s) => !$s->absenHariIni)->count(),
            'sakit' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 's')->count(),
            'ijin' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'i')->count(),
            'alfa' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'a')->count(),
            'dispensasi' => $siswa->filter(fn ($s) => $s->absenHariIni?->keterangan === 'd')->count(),
        ];

        return view('kelas.aktivitas', compact('siswa', 'kelas', 'tanggal', 'rekap'));
    }

    /**
     * Rekap Absensi Mingguan khusus kelas yang diampu wali kelas - sama
     * seperti Kesiswaan > Rekap Absen Mingguan, tapi cuma 1 kelas.
     */
    public function rekapMingguan(Request $request)
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $mingguDipilih = $request->date('minggu') ?? Carbon::now('Asia/Jakarta');
        $awalMinggu = Carbon::parse($mingguDipilih)->startOfWeek(Carbon::MONDAY);
        $akhirMinggu = $awalMinggu->copy()->endOfWeek(Carbon::SUNDAY);

        $idSiswaKelas = Siswa::where('kelas', $kelas)->pluck('id_member');

        $rekap = AbsenSiswa::with('siswa')
            ->whereIn('id_siswa', $idSiswaKelas)
            ->whereIn('keterangan', ['s', 'i', 'a']) // dispensasi (d) sengaja tidak dihitung
            ->whereBetween('tgl_absen', [$awalMinggu->format('Y-m-d'), $akhirMinggu->format('Y-m-d')])
            ->get()
            ->groupBy('id_siswa')
            ->map(function ($grup) {
                return (object) [
                    'siswa' => $grup->first()->siswa,
                    'sakit' => $grup->where('keterangan', 's')->count(),
                    'ijin' => $grup->where('keterangan', 'i')->count(),
                    'alfa' => $grup->where('keterangan', 'a')->count(),
                ];
            })
            ->sortByDesc(fn ($r) => $r->sakit + $r->ijin + $r->alfa)
            ->values();

        return view('kelas.rekap-mingguan', compact('rekap', 'kelas', 'awalMinggu', 'akhirMinggu'));
    }

    /** Data Pelanggaran Siswa - akumulasi poin pelanggaran per siswa, khusus kelas yang diampu. */
    public function pelanggaranSiswa()
    {
        /** @var Member $member */
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);

        $idSiswaKelas = Siswa::where('kelas', $kelas)->pluck('id_member');

        $rujukanMenunggu = \App\Models\RujukanSiswa::with('siswa.nomorWhatsapp')
            ->whereIn('id_siswa', $idSiswaKelas)
            ->where('jenis', 'walikelas')
            ->where('status', 'menunggu')
            ->orderByDesc('created_at')
            ->get();

        $rekap = \App\Models\Pelanggaran::with('siswa')
            ->whereIn('id_siswa', $idSiswaKelas)
            ->get()
            ->groupBy('id_siswa')
            ->map(function ($grup) {
                return (object) [
                    'siswa' => $grup->first()->siswa,
                    'totalPoin' => $grup->sum('poin'),
                    'jumlahKasus' => $grup->count(),
                    'daftar' => $grup->sortByDesc('tgl_pelanggaran')->values(),
                ];
            })
            ->sortByDesc('totalPoin')
            ->values();

        return view('kelas.pelanggaran-siswa', compact('rekap', 'kelas', 'rujukanMenunggu'));
    }

    /** Tindak lanjut rujukan dari Tatib - Konfirmasi Saja / Hubungi Ortu / Ajukan BK / Ajukan Tatib. */
    public function tindakLanjutRujukan(Request $request, \App\Models\RujukanSiswa $rujukanSiswa)
    {
        $data = $request->validate([
            'tindak_lanjut' => ['required', 'in:konfirmasi,hubungi_ortu,ajukan_bk,ajukan_tatib'],
            'catatan_tindak_lanjut' => ['nullable', 'string', 'max:255'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        // "Ajukan BK" mengubah jenis rujukan jadi 'bk' supaya muncul di daftar BK (bukan ditutup).
        $statusBaru = $data['tindak_lanjut'] === 'ajukan_bk' ? 'menunggu' : 'selesai';
        $jenisBaru = $data['tindak_lanjut'] === 'ajukan_bk' ? 'bk' : $rujukanSiswa->jenis;

        $rujukanSiswa->update([
            'jenis' => $jenisBaru,
            'status' => $statusBaru,
            'tindak_lanjut' => $data['tindak_lanjut'],
            'catatan_tindak_lanjut' => $data['catatan_tindak_lanjut'] ?? null,
            'ditindak_oleh' => $member->id,
            'ditindak_at' => now(),
        ]);

        \App\Models\LogAktivitas::catat(
            'pelanggaran',
            $member->nama.' menindaklanjuti rujukan '.($rujukanSiswa->siswa->nama_lengkap ?? '').' ('.\App\Models\RujukanSiswa::TINDAK_LABEL[$data['tindak_lanjut']].').'
        );

        // Kalau "Ajukan Tatib" - arahkan langsung ke form lapor pelanggaran formal.
        if ($data['tindak_lanjut'] === 'ajukan_tatib') {
            return redirect()->route('tatib.lapor-pelanggaran', $rujukanSiswa->siswa)
                ->with('status', 'Silakan lengkapi laporan pelanggaran formal untuk '.($rujukanSiswa->siswa->nama_lengkap ?? '').'.');
        }

        return back()->with('status', 'Tindak lanjut untuk '.($rujukanSiswa->siswa->nama_lengkap ?? '').' berhasil disimpan.');
    }
}
