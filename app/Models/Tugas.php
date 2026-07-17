<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tugas extends Model
{
    protected $table = 'tugas';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['idkelas', 'kelas', 'tgl_tugas', 'idguru', 'tugas', 'gambar', 'keterangan', 'setuju'];

    protected $casts = [
        'tgl_tugas' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'idguru', 'id_guru');
    }
}
