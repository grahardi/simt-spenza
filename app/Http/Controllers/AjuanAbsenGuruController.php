<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\AjuanAbsenGuruPiket;
use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanAbsenGuruController extends Controller
{
    /**
     * Ajukan absen sendiri (Sakit/Ijin/Dispensasi) untuk tanggal pilihan -
     * guru login sendiri, ajuan ini MASUK ANTRIAN ACC dulu (piket/kepsek
     * yang verifikasi baru resmi + kirim notif WA ke Kepsek).
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

        $ajuanMenungguAcc = AjuanAbsenGuruPiket::where('id_guru', $guru->id_guru)
            ->whereDate('tanggal', $tanggal)
            ->first();

        $tugasTanggalItu = Tugas::where('idguru', $guru->id_guru)
            ->whereDate('tgl_tugas', $tanggal)
            ->get()
            ->keyBy('kelas');

        return view('ajuan-absen-guru.index', [
            'guru' => $guru,
            'dariPiket' => false,
            'tanggal' => $tanggal,
            'namaHari' => $namaHari,
            'jadwalHariItu' => $jadwalHariItu,
            'absenTanggalItu' => $absenTanggalItu,
            'ajuanMenungguAcc' => $ajuanMenungguAcc,
            'tugasTanggalItu' => $tugasTanggalItu,
        ]);
    }

    public function simpan(Request $request)
    {
        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'status' => ['required', 'in:s,i,d'], // cuma Sakit/Ijin/Dispensasi, bukan Alfa
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:8192'],
        ]);

        $member = Auth::guard('member')->user();
        $guru = $member->dataGuru;
        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        $atribut = [
            'id_guru' => $guru->id_guru,
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'keterangan' => $data['keterangan'] ?? null,
            'diajukan_oleh' => $member->id,
        ];

        if ($request->hasFile('foto')) {
            $atribut['foto'] = $request->file('foto')->store('absensi-guru', 'public');
        }

        // Ajuan guru sendiri MASUK ANTRIAN dulu - baru resmi & kirim notif WA
        // ke Kepsek SETELAH di-ACC piket/kepsek di menu Absen Guru.
        AjuanAbsenGuruPiket::updateOrCreate(
            ['id_guru' => $guru->id_guru, 'tanggal' => $data['tanggal']],
            $atribut
        );

        return redirect()->route('ajuan-absen-guru.index', ['tanggal' => $data['tanggal']])
            ->with('status', 'Ajuan absen berhasil dikirim, menunggu ACC piket/kepsek.');
    }
}
