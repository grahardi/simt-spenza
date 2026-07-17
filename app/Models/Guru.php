<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = 'guru';
    protected $primaryKey = 'id_guru';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nip', 'nuptk', 'nama', 'jenis_kelamin', 'status', 'aktif',
        'alamat', 'jabatan', 'telepon', 'tgl_lahir',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'aktif' => 'boolean',
    ];
}
