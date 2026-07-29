<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaFoto extends Model
{
    protected $table = 'agenda_foto';

    protected $fillable = ['id_agenda', 'path', 'diupload_oleh'];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'id_agenda');
    }
}
