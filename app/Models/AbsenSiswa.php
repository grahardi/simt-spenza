<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenSiswa extends Model
{
    protected $table = 'absen_siswa';
    protected $primaryKey = 'id_absen_siswa';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['tgl_absen', 'keterangan', 'id_siswa', 'tambahan', 'gambar', 'dari_wa', 'status_wa'];

    protected $casts = [
        'tgl_absen' => 'date',
    ];

    const KETERANGAN_LABEL = [
        's' => 'Sakit',
        'i' => 'Ijin',
        'a' => 'Alpha',
        'd' => 'Dispensasi',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function labelKeterangan(): string
    {
        return self::KETERANGAN_LABEL[$this->keterangan] ?? 'Hadir';
    }

    /**
     * Nama file surat/foto yang bisa DIBACA MANUSIA tanpa perlu database -
     * antisipasi kalau MySQL rusak/hilang, filenya sendiri sudah cukup jelas
     * statusnya apa, siswa siapa, dan tanggal berapa.
     * Contoh: "Sakit_17927_Budi-Santoso_2026-08-20.jpg"
     */
    public static function namaFileSurat(string $keterangan, ?int $idSiswa, ?string $namaSiswa, string $tanggal, string $ekstensi): string
    {
        $label = self::KETERANGAN_LABEL[$keterangan] ?? 'Absen';
        $namaBersih = $namaSiswa ? preg_replace('/[^A-Za-z0-9]+/', '-', trim($namaSiswa)) : 'siswa';
        $namaBersih = trim($namaBersih, '-');
        $waktuSingkat = now('Asia/Jakarta')->format('His'); // biar tidak tertimpa kalau ada >1 file di hari yang sama

        return "{$label}_{$idSiswa}_{$namaBersih}_{$tanggal}_{$waktuSingkat}.{$ekstensi}";
    }
}
