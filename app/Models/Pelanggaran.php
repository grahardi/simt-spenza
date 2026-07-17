<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pelanggaran extends Model
{
    protected $table = 'pelanggaran';
    protected $primaryKey = 'id_langgar';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_siswa', 'tgl_pelanggaran', 'kategori', 'keterangan',
        'poin', 'penanganan', 'id_entry', 'tgl_action',
    ];

    protected $casts = [
        'tgl_pelanggaran' => 'date',
        'tgl_action' => 'date',
    ];

    const KATEGORI = ['Peringatan', 'Ringan', 'Sedang', 'Berat'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_entry', 'id');
    }

    public function sudahDitangani(): bool
    {
        if (!empty($this->poin)) {
            return true;
        }

        return $this->penanganan && strtolower($this->penanganan) !== 'belum';
    }
}
