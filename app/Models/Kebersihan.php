<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Kebersihan extends Model
{
    protected $table = 'kebersihan';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['kelas', 'id_guru', 'status', 'keterangan', 'gambar', 'tanggal', 'jam', 'gambaraksi'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function sudahDitindak(): bool
    {
        return !empty($this->gambaraksi);
    }
}
