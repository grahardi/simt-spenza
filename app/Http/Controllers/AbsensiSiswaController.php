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

        $siswaIds = $absensi->pluck('id_siswa');
        $absenSebelumnya = AbsenSiswa::whereIn('id_siswa', $siswaIds)
            ->whereDate('tgl_absen', $tanggalSebelumnya)
            ->get()
            ->keyBy('id_siswa');

        return view('absensi.index', [
            'absensi' => $absensi,
            'tanggal' => $tanggal,
            'absenSebelumnya' => $absenSebelumnya,
        ]);
    }

    /**
     * Pengganti isiabsen.php - form cari siswa untuk diisi absensinya manual.
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
     * Pengganti caritelat.php + telat.php - catat siswa terlambat hari ini.
     */
    public function telat(Request $request, Siswa $siswa)
    {
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
