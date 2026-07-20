<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function nomorWhatsapp(): HasMany
    {
        return $this->hasMany(GuruWhatsapp::class, 'id_guru', 'id_guru');
    }

    /** Data akun login guru ini - dipakai buat ambil pangkat/jabatan_dinas untuk surat dinas. */
    public function member(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Member::class, 'id_guru', 'id_guru');
    }
}
