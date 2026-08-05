<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaPenanggungJawab extends Model
{
    protected $table = 'agenda_penanggung_jawab';

    protected $fillable = ['id_agenda', 'id_guru', 'jabatan'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
