<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DknUpload extends Model
{
    protected $table = 'dkn_uploads';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['kode_mapel', 'nama_kelas', 'nama_file'];

    protected $casts = [
        'uploaded_at' => 'datetime',
    ];
}
