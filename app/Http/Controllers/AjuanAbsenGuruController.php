<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\Member;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanAbsenGuruController extends Controller
{
    /** Khusus piket - halaman pilih guru dulu, sebelum masuk ke form ajuan. */
    public function pilihGuru()
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('ajuan-absen-guru.pilih-guru', compact('daftarGuru'));
    }

    /**
     * Ajukan absen (Sakit/Ijin/Dispensasi) untuk tanggal pilihan - dipakai
     * 2 cara: guru ajukan sendiri (tanpa {guru} di URL, pakai akun sendiri),
     * atau piket ajukan atas nama guru tertentu (lewat {guru} di URL, setelah
     * pilih dari pilihGuru()).
     */
    public function index(Request $request, ?Guru $guru = null)
    {
        $dariPiket = $guru !== null;

        if (!$dariPiket) {
            $member = Auth::guard('member')->user();
            $guru = $member->dataGuru;
            abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');
        }

        $tanggal = Carbon::parse($request->input('tanggal', Carbon::today('Asia/Jakarta')->toDateString()));
        $namaHari = strtoupper($tanggal->translatedFormat('l'));

        $jadwalHariItu = DataJadwal::where('kodeguru', $guru->id_guru)
            ->where('hari', $namaHari)
            ->orderBy('jamhari')
            ->get();

        $absenTanggalItu = AbsensiGuru::where('id_guru', $guru->id_guru)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $tugasTanggalItu = Tugas::where('idguru', $guru->id_guru)
            ->whereDate('tgl_tugas', $tanggal)
            ->get()
            ->keyBy('kelas');

        return view('ajuan-absen-guru.index', [
            'guru' => $guru,
            'dariPiket' => $dariPiket,
            'tanggal' => $tanggal,
            'namaHari' => $namaHari,
            'jadwalHariItu' => $jadwalHariItu,
            'absenTanggalItu' => $absenTanggalItu,
            'tugasTanggalItu' => $tugasTanggalItu,
        ]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'id_guru' => ['nullable', 'integer', 'exists:guru,id_guru'],
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,d'], // cuma Sakit/Ijin/Dispensasi, bukan Alfa
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $member = Auth::guard('member')->user();

        // Kalau id_guru dikirim (dari alur piket pilih guru), pakai itu -
        // tapi cuma boleh kalau akun yang login memang piket/admin/kesiswaan.
        // Kalau tidak dikirim, pakai guru yang terhubung ke akun sendiri.
        if (!empty($data['id_guru']) && ($member->hasRole('piket') || $member->hasRole('admin') || $member->hasRole('kesiswaan'))) {
            $idGuru = (int) $data['id_guru'];
        } else {
            $guruSendiri = $member->dataGuru;
            abort_if(!$guruSendiri, 403, 'Akun ini tidak terhubung ke data guru manapun.');
            $idGuru = $guruSendiri->id_guru;
        }

        AbsensiGuru::updateOrCreate(
            ['id_guru' => $idGuru, 'tanggal' => $data['tanggal']],
            [
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
                'dicatat_oleh' => $member->id,
            ]
        );

        $rute = !empty($data['id_guru']) ? 'ajuan-absen-guru.piket.form' : 'ajuan-absen-guru.index';
        $params = !empty($data['id_guru']) ? ['guru' => $idGuru, 'tanggal' => $data['tanggal']] : ['tanggal' => $data['tanggal']];

        return redirect()->route($rute, $params)->with('status', 'Ajuan absen berhasil dikirim.');
    }
}
