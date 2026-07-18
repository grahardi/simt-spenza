<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Karyawan extends Model
{
    protected $table = 'karyawan';
    protected $primaryKey = 'id_karyawan';

    protected $fillable = [
        'nip', 'nama', 'jenis_kelamin', 'jabatan', 'status',
        'alamat', 'telepon', 'tgl_lahir', 'aktif',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'aktif' => 'boolean',
    ];
}
