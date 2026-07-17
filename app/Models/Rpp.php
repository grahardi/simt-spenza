<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rpp extends Model
{
    protected $table = 'rpp';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['id_guru', 'bulan', 'tanggal', 'namafile', 'status'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function sudahDisetujui(): bool
    {
        return $this->status === '2';
    }
}
