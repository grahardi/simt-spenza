<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KartuUjian extends Model
{
    protected $table = 'kartu_ujian';

    protected $fillable = ['id_siswa', 'password', 'ruang', 'nokursi'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
