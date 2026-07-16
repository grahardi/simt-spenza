<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsenSiswa extends Model
{
    protected $table = 'absen_siswa';

    protected $fillable = ['siswa_id', 'tanggal', 'keterangan', 'gambar', 'diinput_oleh'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    const KETERANGAN_LABEL = [
        'h' => 'Hadir',
        's' => 'Sakit',
        'i' => 'Ijin',
        'a' => 'Alpha',
        'd' => 'Dispensasi',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class);
    }

    public function labelKeterangan(): string
    {
        return self::KETERANGAN_LABEL[$this->keterangan] ?? 'Tidak diketahui';
    }
}
