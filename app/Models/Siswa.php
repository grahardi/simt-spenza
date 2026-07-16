<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Siswa extends Model
{
    protected $table = 'siswa';

    protected $fillable = [
        'nis', 'nama_lengkap', 'kelas_id', 'jenis_kelamin',
        'whatsapp', 'alamat', 'foto', 'tanggal_gabung',
    ];

    protected $casts = [
        'tanggal_gabung' => 'date',
    ];

    public function kelas(): BelongsTo
    {
        return $this->belongsTo(Kelas::class);
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsenSiswa::class);
    }

    /** Absensi pada tanggal tertentu (dipakai untuk cek "absensi kemarin" seperti kode lama). */
    public function absenPadaTanggal(string $tanggal): ?AbsenSiswa
    {
        return $this->absensi()->where('tanggal', $tanggal)->first();
    }
}
