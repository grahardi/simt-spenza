<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaporKeagamaan extends Model
{
    protected $table = 'lapor_keagamaan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['id_siswa', 'tgl_kegiatan', 'pelanggaran', 'dispensasi', 'keterangan', 'id_entry'];

    protected $casts = [
        'tgl_kegiatan' => 'date',
    ];

    const LABEL = [
        'halangan' => 'Halangan',
        'membolos' => 'Bolos',
        'ijin' => 'Ijin',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_entry', 'id');
    }

    public function label(): string
    {
        return self::LABEL[$this->pelanggaran] ?? $this->pelanggaran;
    }
}
