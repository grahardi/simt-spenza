<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PendampinganFoto extends Model
{
    protected $table = 'pendampingan_foto';

    protected $fillable = ['id_pendampingan', 'path'];

    public function pendampingan(): BelongsTo
    {
        return $this->belongsTo(PendampinganWali::class, 'id_pendampingan');
    }
}
