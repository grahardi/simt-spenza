<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SiswaWhatsapp extends Model
{
    protected $table = 'siswa_whatsapp';

    protected $fillable = ['id_siswa', 'nomor', 'label'];

    const MAKSIMAL_PER_SISWA = 3;

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
