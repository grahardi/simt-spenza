<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataJadwal extends Model
{
    protected $table = 'datajadwal';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['kodejam', 'jamhari', 'hari', 'waktu', 'kodeguru', 'mapel', 'kodekelas', 'kelas'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'kodeguru', 'id_guru');
    }
}
