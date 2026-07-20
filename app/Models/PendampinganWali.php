<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PendampinganWali extends Model
{
    protected $table = 'pendampingan_wali';

    protected $fillable = ['id_guru', 'tanggal_waktu', 'kategori', 'judul', 'deskripsi', 'foto'];

    protected $casts = ['tanggal_waktu' => 'datetime'];

    const KATEGORI_PILIHAN = ['Pendampingan']; // baru 1 dulu, gampang ditambah nanti

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function peserta(): BelongsToMany
    {
        return $this->belongsToMany(Siswa::class, 'pendampingan_peserta', 'id_pendampingan', 'id_siswa', 'id', 'id_member');
    }
}
