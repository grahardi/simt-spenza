<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\Keterlambatan;
use App\Models\Pelanggaran;
use App\Models\Siswa;

class ProfilSiswaController extends Controller
{
    /**
     * Pengganti tampil_detail.php (index.php?halaman=detail&id=...) -
     * profil lengkap 1 siswa: biodata + riwayat absensi + keterlambatan +
     * pelanggaran.
     */
    public function show(Siswa $siswa)
    {
        $absensi = AbsenSiswa::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->limit(50)
            ->get();

        $keterlambatan = Keterlambatan::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_absen')
            ->limit(50)
            ->get();

        $pelanggaran = Pelanggaran::where('id_siswa', $siswa->id_member)
            ->orderByDesc('tgl_pelanggaran')
            ->limit(50)
            ->get();

        return view('siswa.profil', compact('siswa', 'absensi', 'keterlambatan', 'pelanggaran'));
    }
}
