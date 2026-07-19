<?php

namespace App\Http\Controllers;

use App\Models\Siswa;
use App\Models\UksKunjungan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UksController extends Controller
{
    /**
     * Fitur 1: Siswa Sakit - cari siswa (filter kelas opsional), tombol
     * "Sakit" per siswa buat catat masuk UKS.
     */
    public function cari(Request $request)
    {
        $siswa = null;
        $cari = trim((string) $request->input('cari'));
        $kelasFilter = $request->input('kelas');

        if ($cari !== '' || $kelasFilter) {
            $siswa = Siswa::query()
                ->when($cari !== '', function ($q) use ($cari) {
                    $q->where(function ($qq) use ($cari) {
                        $qq->where('nama_lengkap', 'like', '%'.$cari.'%')
                            ->orWhere('id_member', 'like', '%'.$cari.'%');
                    });
                })
                ->when($kelasFilter, fn ($q) => $q->where('kelas', $kelasFilter))
                ->orderBy('nama_lengkap')
                ->limit(50)
                ->get();

            $sedangDiUks = UksKunjungan::whereIn('id_siswa', $siswa->pluck('id_member'))
                ->where('status', 'di_uks')
                ->get()
                ->keyBy('id_siswa');

            $siswa->each(function ($s) use ($sedangDiUks) {
                $s->sedangDiUks = $sedangDiUks->get($s->id_member);
            });
        }

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('uks.cari', compact('siswa', 'cari', 'kelasFilter', 'daftarKelas'));
    }

    /** Simpan siswa masuk UKS karena sakit (keterangan opsional). */
    public function simpanSakit(Request $request, Siswa $siswa)
    {
        $data = $request->validate(['keterangan_sakit' => ['nullable', 'string', 'max:255']]);

        UksKunjungan::create([
            'id_siswa' => $siswa->id_member,
            'keterangan_sakit' => $data['keterangan_sakit'] ?? null,
            'status' => 'di_uks',
            'tanggal' => Carbon::today(),
            'waktu_masuk' => now(),
            'dicatat_oleh' => Auth::guard('member')->id(),
        ]);

        return back()->with('status', $siswa->nama_lengkap.' berhasil dicatat masuk UKS.');
    }

    /**
     * Fitur 2: List Siswa di UKS - yang statusnya masih 'di_uks' hari ini,
     * dengan tombol Penanganan.
     */
    public function list(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();

        $daftar = UksKunjungan::with('siswa')
            ->whereDate('tanggal', $tanggal)
            ->orderByDesc('waktu_masuk')
            ->get();

        return view('uks.list', ['daftar' => $daftar, 'tanggal' => \Illuminate\Support\Carbon::parse($tanggal)]);
    }

    /** Update penanganan (Kembali ke Kelas / Pulang Dijemput / Puskesmas / Lainnya). */
    public function penanganan(Request $request, UksKunjungan $uksKunjungan)
    {
        $data = $request->validate([
            'status' => ['required', 'in:kembali_kelas,pulang_dijemput,puskesmas,lainnya'],
            'keterangan_penanganan' => ['nullable', 'string', 'max:255'],
        ]);

        $uksKunjungan->update([
            'status' => $data['status'],
            'keterangan_penanganan' => $data['keterangan_penanganan'] ?? null,
            'waktu_selesai' => now(),
        ]);

        return back()->with('status', ($uksKunjungan->siswa->nama_lengkap ?? 'Siswa').' - '.$uksKunjungan->labelStatus().'.');
    }

    /**
     * Fitur 3: Panggilan Wali Murid - cari siswa (sama seperti fitur 1),
     * plus daftar siswa yang sedang di UKS dengan tombol WA manual (wa.me).
     */
    public function panggilan(Request $request)
    {
        $siswa = null;
        $cari = trim((string) $request->input('cari'));
        $kelasFilter = $request->input('kelas');

        if ($cari !== '' || $kelasFilter) {
            $siswa = Siswa::query()
                ->when($cari !== '', function ($q) use ($cari) {
                    $q->where(function ($qq) use ($cari) {
                        $qq->where('nama_lengkap', 'like', '%'.$cari.'%')
                            ->orWhere('id_member', 'like', '%'.$cari.'%');
                    });
                })
                ->when($kelasFilter, fn ($q) => $q->where('kelas', $kelasFilter))
                ->orderBy('nama_lengkap')
                ->limit(50)
                ->with('nomorWhatsapp')
                ->get();
        }

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        $sedangDiUks = UksKunjungan::with(['siswa.nomorWhatsapp'])
            ->whereDate('tanggal', Carbon::today())
            ->where('status', 'di_uks')
            ->orderByDesc('waktu_masuk')
            ->get();

        return view('uks.panggilan', compact('siswa', 'cari', 'kelasFilter', 'daftarKelas', 'sedangDiUks'));
    }
}
