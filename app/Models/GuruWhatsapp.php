<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuruWhatsapp extends Model
{
    protected $table = 'guru_whatsapp';

    protected $fillable = ['id_guru', 'nomor'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
