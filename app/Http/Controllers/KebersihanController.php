<?php

namespace App\Http\Controllers;

use App\Models\Kebersihan;
use App\Models\Kelas;
use App\Models\Member;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KebersihanController extends Controller
{
    /** Pengganti bersihkelas.php - grid kelas untuk lapor kelas kotor. */
    public function kelasGrid()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('kebersihan.pilih-kelas', compact('daftarKelas'));
    }

    /** Pengganti kebersihan.php - form lapor kelas kotor (guru yang sedang mengajar di kelas itu). */
    public function lapor(string $kelas)
    {
        return view('kebersihan.lapor', compact('kelas'));
    }

    /**
     * Pengganti warningbersih.php - simpan laporan kelas kotor + foto.
     * Perbaikan dari lama: foto disimpan lewat storage Laravel (bukan
     * move_uploaded_file manual ke folder publik langsung).
     */
    public function simpan(Request $request, string $kelas)
    {
        $data = $request->validate([
            'foto' => ['required', 'image', 'max:2048'],
            'catatan' => ['nullable', 'string', 'max:100'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        Kebersihan::create([
            'kelas' => $kelas,
            'id_guru' => $member->id_guru,
            'status' => 'Belum',
            'keterangan' => $data['catatan'] ?? 'Belum',
            'gambar' => $request->file('foto')->store('kebersihan', 'public'),
            'tanggal' => Carbon::today()->toDateString(),
            'jam' => Carbon::now('Asia/Jakarta')->format('H:i:s'),
        ]);

        return redirect()->route('kebersihan.index')->with('status', 'Laporan kebersihan kelas '.$kelas.' berhasil dikirim.');
    }

    /** Pengganti bersihlist.php - daftar semua laporan, dengan filter tanggal. */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $laporan = Kebersihan::with('guru')
            ->whereDate('tanggal', $tanggal)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('kebersihan.index', compact('laporan', 'tanggal'));
    }

    /**
     * Pengganti updatebersih.php (di kode lama file ini KOSONG, fitur belum
     * pernah selesai dibangun) - upload bukti sudah ditindak/dibersihkan.
     */
    public function tindak(Request $request, Kebersihan $lapor)
    {
        $request->validate([
            'foto_aksi' => ['required', 'image', 'max:2048'],
        ]);

        $lapor->update([
            'gambaraksi' => $request->file('foto_aksi')->store('kebersihan-aksi', 'public'),
            'status' => 'Selesai',
        ]);

        return back()->with('status', 'Bukti tindakan untuk kelas '.$lapor->kelas.' berhasil disimpan.');
    }

    /** Galeri sederhana - foto sebelum/sesudah untuk laporan yang sudah ditindak. */
    public function galeri(Request $request)
    {
        $laporan = Kebersihan::with('guru')
            ->whereNotNull('gambaraksi')
            ->orderByDesc('id')
            ->paginate(12)
            ->withQueryString();

        return view('kebersihan.galeri', compact('laporan'));
    }
}
