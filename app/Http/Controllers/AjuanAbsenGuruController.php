<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\DataJadwal;
use App\Models\Member;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanAbsenGuruController extends Controller
{
    /**
     * Guru ajukan absen (Sakit/Ijin/Dispensasi) sendiri untuk tanggal
     * pilihan - beda dari "Absen Guru" (piket) yang selalu untuk hari ini.
     * Pakai id_guru dari akun yang sedang login, bukan dari URL.
     */
    public function index(Request $request)
    {
        $member = Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

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
            'tanggal' => $tanggal,
            'namaHari' => $namaHari,
            'jadwalHariItu' => $jadwalHariItu,
            'absenTanggalItu' => $absenTanggalItu,
            'tugasTanggalItu' => $tugasTanggalItu,
        ]);
    }

    public function simpan(Request $request)
    {
        $member = Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,d'], // guru cuma boleh ajukan Sakit/Ijin/Dispensasi, bukan Alfa
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        AbsensiGuru::updateOrCreate(
            ['id_guru' => $guru->id_guru, 'tanggal' => $data['tanggal']],
            [
                'status' => $data['status'],
                'keterangan' => $data['keterangan'] ?? null,
                'dicatat_oleh' => $member->id,
            ]
        );

        return redirect()->route('ajuan-absen-guru.index', ['tanggal' => $data['tanggal']])
            ->with('status', 'Ajuan absen berhasil dikirim.');
    }
}
