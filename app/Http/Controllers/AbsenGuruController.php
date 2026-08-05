<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AjuanAbsenGuruPiket;
use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\Member;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AbsenGuruController extends Controller
{
    /** List terpadu - guru yang SUDAH diacc absennya hari ini + yang MENUNGGU ACC. */
    public function index()
    {
        $hariIni = Member::namaHariJakartaHuruBesar();
        $tanggalHariIni = now('Asia/Jakarta')->toDateString();

        $sudahDiacc = AbsensiGuru::with('guru')
            ->whereDate('tanggal', $tanggalHariIni)
            ->get()
            ->filter(fn ($a) => $a->guru !== null)
            ->map(fn ($absen) => $this->lengkapiJadwalTugas($absen, $absen->guru, $hariIni, $tanggalHariIni))
            ->values();

        $menungguAcc = AjuanAbsenGuruPiket::with('guru')
            ->whereDate('tanggal', $tanggalHariIni)
            ->orderBy('created_at')
            ->get()
            ->filter(fn ($a) => $a->guru !== null)
            ->map(fn ($ajuan) => $this->lengkapiJadwalTugas($ajuan, $ajuan->guru, $hariIni, $tanggalHariIni))
            ->values();

        return view('absen-guru.index', compact('sudahDiacc', 'menungguAcc'));
    }

    private function lengkapiJadwalTugas($record, Guru $guru, string $hariIni, string $tanggalHariIni): object
    {
        $jadwalHariIni = DataJadwal::where('kodeguru', $guru->id_guru)
            ->where('hari', $hariIni)
            ->orderBy('jamhari')
            ->get();

        $tugasHariIni = Tugas::where('idguru', $guru->id_guru)
            ->whereDate('tgl_tugas', $tanggalHariIni)
            ->get()
            ->keyBy('kelas');

        return (object) [
            'record' => $record,
            'guru' => $guru,
            'jadwal' => $jadwalHariIni,
            'tugas' => $tugasHariIni,
        ];
    }

    /** Form pilih guru untuk Ajuan Manual (piket) - cari nama, bukan dropdown semua guru. */
    public function pilihGuru(Request $request)
    {
        $cari = trim((string) $request->input('cari'));
        $daftarGuru = null;

        if ($cari !== '') {
            $daftarGuru = Guru::where('nama', 'like', '%'.$cari.'%')
                ->orderBy('nama')
                ->limit(20)
                ->get();
        }

        return view('absen-guru.pilih-guru', compact('daftarGuru', 'cari'));
    }

    /** Riwayat Absen Guru - semua riwayat (bukan cuma hari ini), klik nama untuk lihat detail (keterangan, surat, tugas). */
    public function riwayatAbsen(Request $request)
    {
        $cari = trim((string) $request->input('cari'));

        $riwayat = AbsensiGuru::with('guru')
            ->when($cari !== '', fn ($q) => $q->whereHas('guru', fn ($g) => $g->where('nama', 'like', '%'.$cari.'%')))
            ->orderByDesc('tanggal')
            ->paginate(20)
            ->withQueryString();

        // Detail tugas per baris (cuma untuk yang tampil di halaman ini)
        $idGuruHalamanIni = collect($riwayat->items())->pluck('id_guru')->unique();
        $tanggalHalamanIni = collect($riwayat->items())->pluck('tanggal')->map(fn ($t) => $t->toDateString())->unique();

        $tugasSemua = Tugas::whereIn('idguru', $idGuruHalamanIni)
            ->get()
            ->groupBy(fn ($t) => $t->idguru.'|'.\Illuminate\Support\Carbon::parse($t->tgl_tugas)->toDateString());

        return view('absen-guru.riwayat', compact('riwayat', 'cari', 'tugasSemua'));
    }

    public function formAjuan(Guru $guru)
    {
        return view('absen-guru.form-ajuan', compact('guru'));
    }

    /** Simpan ajuan manual (piket) - LANGSUNG resmi (piket sendiri yang mengesahkan, tidak perlu ACC lagi). */
    public function simpanAjuan(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:8192'],
        ]);

        $atribut = [
            'status' => $data['status'],
            'keterangan' => $data['keterangan'] ?? null,
            'dicatat_oleh' => Auth::guard('member')->id(),
        ];

        if ($request->hasFile('foto')) {
            $atribut['foto'] = $request->file('foto')->store('absensi-guru', 'public');
        }

        AbsensiGuru::updateOrCreate(
            ['id_guru' => $guru->id_guru, 'tanggal' => $data['tanggal']],
            $atribut
        );

        \App\Services\NotifikasiAbsensiGuruService::kirimKeKepsek(
            $guru, $data['status'], $data['keterangan'] ?? null, $atribut['foto'] ?? null, $data['tanggal']
        );

        return redirect()->route('absen-guru.index')->with('status', 'Absensi '.$guru->nama.' berhasil dicatat.');
    }

    /** ACC ajuan - baru di sini masuk ke absensi resmi & kirim notif WA ke Kepsek. */
    public function acc(AjuanAbsenGuruPiket $ajuanAbsenGuruPiket)
    {
        AbsensiGuru::updateOrCreate(
            ['id_guru' => $ajuanAbsenGuruPiket->id_guru, 'tanggal' => $ajuanAbsenGuruPiket->tanggal->toDateString()],
            [
                'status' => $ajuanAbsenGuruPiket->status,
                'keterangan' => $ajuanAbsenGuruPiket->keterangan,
                'foto' => $ajuanAbsenGuruPiket->foto,
                'dicatat_oleh' => Auth::guard('member')->id(),
            ]
        );

        \App\Services\NotifikasiAbsensiGuruService::kirimKeKepsek(
            $ajuanAbsenGuruPiket->guru,
            $ajuanAbsenGuruPiket->status,
            $ajuanAbsenGuruPiket->keterangan,
            $ajuanAbsenGuruPiket->foto,
            $ajuanAbsenGuruPiket->tanggal->toDateString()
        );

        $nama = $ajuanAbsenGuruPiket->guru->nama ?? 'guru';
        $ajuanAbsenGuruPiket->delete();

        return back()->with('status', 'Ajuan '.$nama.' berhasil di-ACC dan sudah masuk absensi resmi.');
    }

    /** Tolak ajuan - hapus tanpa masuk absensi resmi, tanpa notif WA. */
    public function tolak(AjuanAbsenGuruPiket $ajuanAbsenGuruPiket)
    {
        $nama = $ajuanAbsenGuruPiket->guru->nama ?? 'guru';
        if ($ajuanAbsenGuruPiket->foto) {
            Storage::disk('public')->delete($ajuanAbsenGuruPiket->foto);
        }
        $ajuanAbsenGuruPiket->delete();

        return back()->with('status', 'Ajuan '.$nama.' ditolak.');
    }
}
