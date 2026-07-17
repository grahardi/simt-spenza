<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warning extends Model
{
    protected $table = 'warning';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_guru', 'tgl_warning', 'warning', 'kategori', 'keterangan',
        'jam', 'kelas', 'gambar', 'id_entry', 'aksi', 'aksigambar',
    ];

    protected $casts = [
        'tgl_warning' => 'date',
    ];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_entry', 'id');
    }

    public function belumDitanggapi(): bool
    {
        return !$this->aksi || strtolower($this->aksi) === 'belum';
    }
}
