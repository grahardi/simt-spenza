<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Keterlambatan;
use App\Models\Siswa;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AbsensiSiswaController extends Controller
{
    /**
     * Pengganti absenjelas.php - rekap absensi per tanggal.
     */
    public function index(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $tanggalSebelumnya = $tanggal->isMonday()
            ? $tanggal->copy()->subDays(2)
            : $tanggal->copy()->subDay();

        $absensi = AbsenSiswa::with('siswa')
            ->whereDate('tgl_absen', $tanggal)
            ->orderBy(
                Siswa::select('kelas')
                    ->whereColumn('datasiswa.id_member', 'absen_siswa.id_siswa')
            )
            ->paginate(20)
            ->withQueryString();

        $rekap = AbsenSiswa::whereDate('tgl_absen', $tanggal)
            ->selectRaw('keterangan, count(*) as jumlah')
            ->groupBy('keterangan')
            ->pluck('jumlah', 'keterangan');

        $siswaIds = $absensi->pluck('id_siswa');
        $absenSebelumnya = AbsenSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tgl_absen', $tanggalSebelumnya)
            ->get()
            ->keyBy('id_siswa');

        return view('absensi.index', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'absenSebelumnya' => $absenSebelumnya,
            'rekap' => $rekap,
        ]);
    }

    /**
     * Menampilkan foto bukti sakit/ijin (link yang dulu belum pernah dibuat rute-nya).
     */
    public function foto(AbsenSiswa $absen)
    {
        abort_unless($absen->gambar, 404);

        return redirect(\Illuminate\Support\Facades\Storage::url($absen->gambar));
    }

    /**
     * Pengganti isiabsen.php - form cari siswa untuk diisi absensinya manual.
     * Menampilkan status absensi hari ini kalau siswa sudah pernah ditandai.
     */
    public function isi(Request $request)
    {
        $siswa = null;
        $cari = trim((string) $request->input('cari'));

        if ($cari !== '') {
            $siswa = Siswa::query()
                ->where(function ($query) use ($cari) {
                    $query->where('nama_lengkap', 'like', '%'.$cari.'%')
                        ->orWhere('id_member', 'like', '%'.$cari.'%');
                })
                ->orderByDesc('id_member')
                ->limit(20)
                ->get();

            $absenHariIni = AbsenSiswa::whereIn('id_siswa', $siswa->pluck('id_member'))
                ->whereDate('tgl_absen', Carbon::today())
                ->get()
                ->keyBy('id_siswa');

            $siswa->each(function ($s) use ($absenHariIni) {
                $s->absenHariIni = $absenHariIni->get($s->id_member);
            });
        }

        return view('absensi.isi', ['siswa' => $siswa, 'cari' => $cari]);
    }

    /**
     * Pengganti absensi.php - insert/update absensi hari ini untuk 1 siswa.
     * Dulu ditulis via link GET (rawan double-submit & CSRF) - sekarang POST + validasi.
     * Sakit/Ijin bisa sertakan foto bukti (opsional - klik "Absen" tanpa foto tetap tersimpan).
     */
    public function tandai(Request $request, Siswa $siswa)
    {
        $data = $request->validate([
            'keterangan' => ['required', 'in:h,s,i,a,d'],
            'catatan' => ['nullable', 'string', 'max:100'],
            'foto' => ['nullable', 'image', 'max:2048'],
        ]);

        $atribut = [
            'keterangan' => $data['keterangan'],
            'tambahan' => $data['catatan'] ?? null,
        ];

        if ($request->hasFile('foto')) {
            $atribut['gambar'] = $request->file('foto')->store('absensi', 'public');
        }

        AbsenSiswa::updateOrCreate(
            ['id_siswa' => $siswa->id_member, 'tgl_absen' => Carbon::today()->toDateString()],
            $atribut
        );

        return back()->with('status', 'Absensi '.$siswa->nama_lengkap.' berhasil dicatat.');
    }

    /**
     * Hapus 1 record absensi (dipakai dari modal "Ubah" di halaman rekap).
     */
    public function hapus(AbsenSiswa $absen)
    {
        $nama = $absen->siswa->nama_lengkap ?? 'siswa';
        $absen->delete();

        return back()->with('status', 'Absensi '.$nama.' berhasil dihapus.');
    }

    /**
     * Pengganti caritelat.php + telat.php - catat siswa terlambat hari ini.
     * Ditolak kalau siswa sudah tercatat absen (sakit/ijin/alfa/dispensasi) hari ini -
     * tidak masuk akal berstatus 2 hal sekaligus.
     */
    public function telat(Request $request, Siswa $siswa)
    {
        $sudahAbsen = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->whereDate('tgl_absen', Carbon::today())
            ->exists();

        if ($sudahAbsen) {
            return back()->with('status', $siswa->nama_lengkap.' sudah tercatat absen hari ini, tidak bisa ditandai terlambat juga.');
        }

        Keterlambatan::create([
            'id_siswa' => $siswa->id_member,
            'tgl_absen' => Carbon::today()->toDateString(),
            'keterangan' => 't',
        ]);

        return back()->with('status', $siswa->nama_lengkap.' dicatat terlambat hari ini.');
    }

    /**
     * Pengganti listtelat.php/telatkelas.php - daftar siswa terlambat per tanggal.
     */
    public function listTelat(Request $request)
    {
        $tanggal = $request->date('tgl') ?? Carbon::today();
        $tanggal = Carbon::parse($tanggal);

        $data = Keterlambatan::with('siswa')
            ->whereDate('tgl_absen', $tanggal)
            ->paginate(20)
            ->withQueryString();

        return view('absensi.telat', ['data' => $data, 'tanggal' => $tanggal]);
    }
}
