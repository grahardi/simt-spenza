<?php

namespace App\Http\Controllers;

use App\Models\PelanggaranKeagamaan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PelanggaranKeagamaanController extends Controller
{
    /** List kelas dalam bentuk kartu - dipilih guru untuk mulai catat. */
    public function pilihKelas()
    {
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('pelanggaran-keagamaan.pilih-kelas', compact('daftarKelas'));
    }

    /** List siswa 1 kelas - tiap nama ada 3 tombol: Ijin, Halangan, Kabur. */
    public function formKelas(string $kelas)
    {
        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap')->get();

        $tanggalHariIni = now('Asia/Jakarta')->toDateString();
        $sudahDicatatHariIni = PelanggaranKeagamaan::where('kelas', $kelas)
            ->whereDate('tanggal', $tanggalHariIni)
            ->get()
            ->keyBy('id_siswa');

        return view('pelanggaran-keagamaan.form-kelas', compact('kelas', 'siswa', 'sudahDicatatHariIni'));
    }

    /** Simpan/ubah status 1 siswa untuk hari ini (klik tombol Ijin/Halangan/Kabur). */
    public function simpan(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'status' => ['required', 'in:ijin,halangan,kabur'],
        ]);

        PelanggaranKeagamaan::updateOrCreate(
            ['id_siswa' => $siswa->id_member, 'tanggal' => now('Asia/Jakarta')->toDateString()],
            [
                'kelas' => $siswa->kelas,
                'status' => $data['status'],
                'dicatat_oleh' => Auth::guard('member')->id(),
            ]
        );

        return back()->with('status', $siswa->nama_lengkap.' dicatat '.PelanggaranKeagamaan::LABEL_STATUS[$data['status']].'.');
    }

    /** Hapus catatan (batal, salah klik). */
    public function hapus(PelanggaranKeagamaan $pelanggaranKeagamaan)
    {
        $kelas = $pelanggaranKeagamaan->kelas;
        $pelanggaranKeagamaan->delete();

        return redirect()->route('pelanggaran-keagamaan.form-kelas', $kelas)->with('status', 'Catatan dihapus.');
    }

    /** Rekap Per Hari - khusus Admin Keagamaan, bisa pilih tanggal. */
    public function rekapHarian(Request $request)
    {
        $tanggal = $request->input('tanggal', now('Asia/Jakarta')->toDateString());

        $rekap = PelanggaranKeagamaan::with('siswa')
            ->whereDate('tanggal', $tanggal)
            ->orderBy('kelas')
            ->orderBy('id_siswa')
            ->paginate(20)
            ->withQueryString();

        return view('pelanggaran-keagamaan.rekap-harian', compact('rekap', 'tanggal'));
    }

    /** Rekap Terbanyak - siswa dengan jumlah Ijin/Halangan/Kabur terbanyak (akumulasi semua tanggal). */
    public function rekapTerbanyak(Request $request)
    {
        $status = $request->input('status', 'kabur');

        $rekap = PelanggaranKeagamaan::selectRaw('id_siswa, kelas, count(*) as jumlah')
            ->where('status', $status)
            ->groupBy('id_siswa', 'kelas')
            ->orderByDesc('jumlah')
            ->with('siswa')
            ->paginate(20)
            ->withQueryString();

        return view('pelanggaran-keagamaan.rekap-terbanyak', compact('rekap', 'status'));
    }
}
