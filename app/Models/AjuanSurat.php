<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AjuanSurat extends Model
{
    protected $table = 'ajuan_surat';

    protected $fillable = ['id_guru', 'jenis_surat', 'data', 'file_pendukung', 'status', 'nomor_surat', 'file_pdf', 'diproses_oleh', 'diproses_at'];

    protected $casts = [
        'data' => 'array',
        'diproses_at' => 'datetime',
    ];

    const JENIS_LABEL = ['sppd' => 'SPPD (Surat Tugas & Perjalanan Dinas)', 'surat_permohonan' => 'Surat Permohonan'];
    const STATUS_LABEL = ['menunggu' => 'Menunggu', 'diproses' => 'Diproses', 'selesai' => 'Selesai'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function labelJenis(): string
    {
        return self::JENIS_LABEL[$this->jenis_surat] ?? $this->jenis_surat;
    }

    public function labelStatus(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }
}
