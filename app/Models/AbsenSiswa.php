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
}
