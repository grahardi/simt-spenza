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

    /** Form pilih guru untuk Ajuan Manual (piket). */
    public function pilihGuru()
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('absen-guru.pilih-guru', compact('daftarGuru'));
    }

    public function formAjuan(Guru $guru)
    {
        return view('absen-guru.form-ajuan', compact('guru'));
    }

    /** Simpan ajuan manual (piket) - MASUK ANTRIAN, belum langsung jadi absensi resmi. */
    public function simpanAjuan(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,d'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:8192'],
        ]);

        $atribut = [
            'id_guru' => $guru->id_guru,
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'keterangan' => $data['keterangan'] ?? null,
            'diajukan_oleh' => Auth::guard('member')->id(),
        ];

        if ($request->hasFile('foto')) {
            $atribut['foto'] = $request->file('foto')->store('absensi-guru', 'public');
        }

        AjuanAbsenGuruPiket::create($atribut);

        return redirect()->route('absen-guru.index')->with('status', 'Ajuan absen '.$guru->nama.' berhasil dikirim, menunggu ACC.');
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
