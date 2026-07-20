<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbsensiGuru extends Model
{
    protected $table = 'absensi_guru';

    protected $fillable = ['id_guru', 'tanggal', 'status', 'keterangan', 'dicatat_oleh'];

    protected $casts = ['tanggal' => 'date'];

    const LABEL_STATUS = ['s' => 'Sakit', 'i' => 'Ijin', 'a' => 'Alfa', 'd' => 'Dispensasi'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function labelStatus(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }
}
