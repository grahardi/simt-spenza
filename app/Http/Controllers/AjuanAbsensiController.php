<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\AjuanAbsensi;
use App\Models\Kelas;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanAbsensiController extends Controller
{
    /**
     * Pengganti laporlistkelas.php - grid kartu kelas (diambil dari tabel `kelas`
     * asli, bukan hasil generate dari data siswa), dikelompokkan warna per
     * tingkat (7/8/9).
     */
    public function pilihKelas()
    {
        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('ajuan-absensi.pilih-kelas', compact('daftarKelas'));
    }

    /**
     * Pengganti laporabsen.php - daftar siswa di 1 kelas untuk diajukan
     * absensinya oleh Admin Absensi (BELUM masuk ke absen_siswa).
     * Kolom "Absen Kemarin" pakai logika cekabsen.php lama: hari Senin
     * cek 2 hari lalu (Jumat), hari lain cek 1 hari lalu (Sabtu/Minggu libur).
     */
    public function ajukan(Request $request, string $kelas)
    {
        $siswa = Siswa::where('kelas', $kelas)
            ->orderBy('nama_lengkap')
            ->get();

        $hariIni = Carbon::today();
        $tanggalSebelumnya = $hariIni->isMonday() ? $hariIni->copy()->subDays(2) : $hariIni->copy()->subDay();

        $ajuanHariIni = AjuanAbsensi::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $hariIni)
            ->get()
            ->keyBy('id_siswa');

        $absenSebelumnya = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $tanggalSebelumnya)
            ->get()
            ->keyBy('id_siswa');

        $absenResmiHariIni = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
            ->whereDate('tgl_absen', $hariIni)
            ->get()
            ->keyBy('id_siswa');

        $siswa->each(function ($s) use ($ajuanHariIni, $absenSebelumnya, $absenResmiHariIni) {
            $s->ajuanHariIni = $ajuanHariIni->get($s->id_member);
            $s->absenSebelumnya = $absenSebelumnya->get($s->id_member);
            $s->absenResmiHariIni = $absenResmiHariIni->get($s->id_member);
        });

        return view('ajuan-absensi.ajukan', ['siswa' => $siswa, 'kelas' => $kelas]);
    }

    /**
     * Pengganti prosesajuan.php - simpan ajuan (belum masuk absensi resmi).
     */
    public function simpan(Request $request, Siswa $siswa)
    {
        $sudahAbsenResmi = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->whereDate('tgl_absen', Carbon::today())
            ->exists();

        if ($sudahAbsenResmi) {
            return back()->with('status', $siswa->nama_lengkap.' sudah tercatat absen resmi hari ini, tidak perlu diajukan lagi.');
        }

        $data = $request->validate([
            'keterangan' => ['required', 'in:s,i,a,d'],
            'catatan' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:8192'],
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
     * Pengganti ajuanabsenedit.php (sisi Admin Absensi) - daftar semua ajuan
     * yang sudah dikirim tapi belum di-ACC piket, dengan filter tanggal
     * (default hari ini), dan tombol Hapus kalau Admin Absensi salah input.
     */
    public function listAjuan(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $ajuan = AjuanAbsensi::with('siswa')
            ->whereDate('tgl_absen', $tanggal)
            ->orderByDesc('id_absen_siswa')
            ->paginate(20)
            ->withQueryString();

        return view('ajuan-absensi.list', compact('ajuan', 'tanggal'));
    }

    /** Admin Absensi hapus ajuan sendiri (salah input, dsb) - beda dari tolak-nya piket. */
    public function hapusAjuan(AjuanAbsensi $ajuan)
    {
        $nama = $ajuan->siswa->nama_lengkap ?? 'siswa';
        $ajuan->delete();

        return back()->with('status', 'Ajuan '.$nama.' berhasil dihapus.');
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
