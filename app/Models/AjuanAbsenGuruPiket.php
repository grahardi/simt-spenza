<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AjuanAbsenGuruPiket extends Model
{
    protected $table = 'ajuan_absen_guru_piket';

    protected $fillable = ['id_guru', 'tanggal', 'status', 'keterangan', 'foto', 'diajukan_oleh', 'diacc_oleh', 'diacc_at'];

    protected $casts = ['tanggal' => 'date', 'diacc_at' => 'datetime'];

    const LABEL_STATUS = ['s' => 'Sakit', 'i' => 'Ijin', 'd' => 'Dispensasi'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function labelStatus(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }
}
