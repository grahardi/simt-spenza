<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\AbsenSiswa;
use App\Models\Keterlambatan;
use App\Models\Pelanggaran;
use App\Models\Warning;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $hariIni = Carbon::today();
        $bulanIni = Carbon::now('Asia/Jakarta')->startOfMonth();

        $stat = [
            'absensi_hari_ini' => AbsenSiswa::whereDate('tgl_absen', $hariIni)->count(),
            'absensi_bulan_ini' => AbsenSiswa::whereDate('tgl_absen', '>=', $bulanIni)->count(),
            'terlambat_hari_ini' => Keterlambatan::whereDate('tgl_absen', $hariIni)->count(),
            'terlambat_bulan_ini' => Keterlambatan::whereDate('tgl_absen', '>=', $bulanIni)->count(),
            'pelanggaran_bulan_ini' => Pelanggaran::whereDate('tgl_pelanggaran', '>=', $bulanIni)->count(),
            'pelanggaran_belum_ditangani' => Pelanggaran::where(function ($q) {
                $q->whereNull('poin')->orWhere('poin', '');
            })->where(function ($q) {
                $q->whereNull('penanganan')->orWhereRaw('LOWER(penanganan) = ?', ['belum']);
            })->count(),
            // "Belum diaksi guru" - laporan Kelas Kosong dari kepsek yang guru
            // bersangkutan belum kasih konfirmasi alasan.
            'notifikasi_belum_diaksi' => Warning::where('kategori', 'Kelas Kosong')->whereNull('aksi')->count(),
        ];

        return view('superadmin.dashboard', compact('stat'));
    }
}
