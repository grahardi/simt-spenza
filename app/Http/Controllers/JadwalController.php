<?php

namespace App\Http\Controllers;

use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\Kelas;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    private array $urutanHari = ['SENIN', 'SELASA', 'RABU', 'KAMIS', 'JUMAT'];

    /**
     * Halaman ini juga dipakai untuk versi PUBLIK (tanpa login) lewat
     * prefix route /jadwal-publik, supaya bisa dibagikan lewat link
     * langsung ke siapa saja (orang tua, dsb).
     */
    private function layout(): string
    {
        return request()->routeIs('jadwal-publik.*') ? 'layouts.publik' : 'layouts.app';
    }

    /** Halaman depan Jadwal - pilih mau lihat berdasarkan Kelas atau Guru. */
    public function index()
    {
        return view('jadwal.pilih', ['layout' => $this->layout()]);
    }

    /** Pengganti laporlistkelas.php - grid kelas dari tabel `kelas` asli. */
    public function kelasGrid()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('jadwal.pilih-kelas', ['daftarKelas' => $daftarKelas, 'layout' => $this->layout()]);
    }

    /** Jadwal 1 minggu penuh (Senin-Jumat) untuk 1 kelas. */
    public function kelasDetail(string $kelas)
    {
        $jadwal = DataJadwal::with('guru')
            ->where('kelas', $kelas)
            ->orderBy('jamhari')
            ->get()
            ->groupBy('hari');

        return view('jadwal.kelas', [
            'kelas' => $kelas,
            'jadwalPerHari' => $jadwal,
            'urutanHari' => $this->urutanHari,
            'layout' => $this->layout(),
        ]);
    }

    /** Cari guru untuk lihat jadwal mengajarnya 1 minggu penuh. */
    public function guruList(Request $request)
    {
        $cari = trim((string) $request->input('cari'));
        $guru = null;

        if ($cari !== '') {
            $guru = Guru::where('nama', 'like', '%'.$cari.'%')
                ->orderBy('nama')
                ->limit(20)
                ->get();
        }

        return view('jadwal.pilih-guru', ['guru' => $guru, 'cari' => $cari, 'layout' => $this->layout()]);
    }

    /** Jadwal 1 minggu penuh (Senin-Jumat) untuk 1 guru, di semua kelas yang diajar. */
    public function guruDetail(Guru $guru)
    {
        $jadwal = DataJadwal::where('kodeguru', $guru->id_guru)
            ->orderBy('jamhari')
            ->get()
            ->groupBy('hari');

        return view('jadwal.guru-detail', [
            'guru' => $guru,
            'jadwalPerHari' => $jadwal,
            'urutanHari' => $this->urutanHari,
            'layout' => $this->layout(),
        ]);
    }
}
