<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KodeGuru extends Model
{
    protected $table = 'kodeguru';

    protected $fillable = ['kode', 'nama_excel', 'mapel', 'id_guru', 'skor_kecocokan'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
