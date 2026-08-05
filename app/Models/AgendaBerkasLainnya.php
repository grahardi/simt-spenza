<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgendaBerkasLainnya extends Model
{
    protected $table = 'agenda_berkas_lainnya';

    protected $fillable = ['id_agenda', 'nama_file', 'path'];

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class, 'id_agenda');
    }
}
