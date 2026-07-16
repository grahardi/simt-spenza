<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Keterlambatan extends Model
{
    protected $table = 'keterlambatan';
    protected $primaryKey = 'id_absen_siswa';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['tgl_absen', 'keterangan', 'id_siswa'];

    protected $casts = [
        'tgl_absen' => 'date',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
