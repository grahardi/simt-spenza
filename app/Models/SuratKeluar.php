<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'kode_surat', 'id_kategori_surat', 'nomor_urut', 'tahun', 'tanggal_surat',
        'tujuan_surat', 'perihal', 'lampiran', 'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
    ];

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriSurat::class, 'id_kategori_surat');
    }

    /**
     * Nomor urut terbesar yang SUDAH terdaftar - dipakai buat mode "Auto"
     * (bukan reset per tahun, tapi urutan terus menerus/global sesuai arahan).
     */
    public static function nomorUrutTerbesar(): int
    {
        return (int) (DB::table('surat_keluar')->max('nomor_urut') ?? 0);
    }

    /**
     * Susun kode surat lengkap: {kode_umum}/{nomor_urut}/{kode_baku}/{tahun}
     * Contoh: 400/123/35.07.301.09.43/2026
     */
    public static function susunKode(KategoriSurat $kategori, int $nomorUrut, ?\DateTimeInterface $tanggal = null): array
    {
        $tanggal = $tanggal ?? now();
        $tahun = (int) $tanggal->format('Y');
        $kodeBaku = PengaturanSurat::ambil()->kode_baku;

        $kodeSurat = "{$kategori->kode}/{$nomorUrut}/{$kodeBaku}/{$tahun}";

        return ['kode_surat' => $kodeSurat, 'nomor_urut' => $nomorUrut, 'tahun' => $tahun];
    }
}
