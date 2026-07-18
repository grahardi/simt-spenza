<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'kode_surat', 'nomor_urut', 'tahun', 'tanggal_surat', 'tujuan_surat',
        'perihal', 'lampiran', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    /** Kode singkatan sekolah dipakai di format nomor surat - sesuaikan kalau beda. */
    const KODE_SEKOLAH = 'SMPN1-TRN';

    const BULAN_ROMAWI = [
        1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
        7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
    ];

    /**
     * Generate nomor urut & kode surat berikutnya untuk tahun berjalan.
     * Format: {nomor_urut}/{KODE_SEKOLAH}/{bulan_romawi}/{tahun}
     * Contoh: 007/SMPN1-TRN/VII/2026
     * Nomor urut reset ke 1 tiap tahun baru.
     */
    public static function nomorBerikutnya(?\DateTimeInterface $tanggal = null): array
    {
        $tanggal = $tanggal ?? now();
        $tahun = (int) $tanggal->format('Y');

        $urutTerakhir = DB::table('surat_keluar')->where('tahun', $tahun)->max('nomor_urut') ?? 0;
        $urutBaru = $urutTerakhir + 1;

        $kode = sprintf('%03d', $urutBaru).'/'.self::KODE_SEKOLAH.'/'.self::BULAN_ROMAWI[(int) $tanggal->format('n')].'/'.$tahun;

        return ['nomor_urut' => $urutBaru, 'tahun' => $tahun, 'kode_surat' => $kode];
    }
}
