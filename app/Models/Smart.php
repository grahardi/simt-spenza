<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Smart extends Model
{
    protected $table = 'smart';
    protected $primaryKey = 'no';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['tanggal', 'jam', 'idguru', 'ket', 'kelas'];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'idguru', 'id_guru');
    }
}
