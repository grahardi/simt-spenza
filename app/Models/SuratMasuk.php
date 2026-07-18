<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SuratMasuk extends Model
{
    protected $table = 'surat_masuk';

    protected $fillable = [
        'nomor_surat', 'asal_surat', 'tanggal_surat', 'tanggal_terima', 'perihal',
        'file_scan', 'disposisi_ke', 'catatan_disposisi', 'status', 'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal_surat' => 'date',
        'tanggal_terima' => 'date',
    ];

    const STATUS_LABEL = [
        'baru' => 'Baru',
        'diproses' => 'Diproses',
        'selesai' => 'Selesai',
    ];

    public function labelStatus(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }
}
