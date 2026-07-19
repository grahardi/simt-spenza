<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UksKunjungan extends Model
{
    protected $table = 'uks_kunjungan';

    protected $fillable = [
        'id_siswa', 'keterangan_sakit', 'status', 'keterangan_penanganan',
        'tanggal', 'waktu_masuk', 'waktu_selesai', 'dicatat_oleh',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'waktu_masuk' => 'datetime',
        'waktu_selesai' => 'datetime',
    ];

    const STATUS_LABEL = [
        'di_uks' => 'Masih di UKS',
        'kembali_kelas' => 'Kembali ke Kelas',
        'pulang_dijemput' => 'Pulang Dijemput',
        'puskesmas' => 'Dirujuk ke Puskesmas',
        'lainnya' => 'Lainnya',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function labelStatus(): string
    {
        return self::STATUS_LABEL[$this->status] ?? $this->status;
    }
}
