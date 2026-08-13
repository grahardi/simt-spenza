<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BansosAjuan extends Model
{
    protected $table = 'bansos_ajuan';

    protected $fillable = ['id_siswa', 'kelas', 'diajukan_oleh', 'keterangan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
