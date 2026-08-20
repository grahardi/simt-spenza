<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelanggaranKeagamaan extends Model
{
    protected $table = 'pelanggaran_keagamaan';

    protected $fillable = ['id_siswa', 'kelas', 'status', 'tanggal', 'dicatat_oleh'];

    protected $casts = ['tanggal' => 'date'];

    const LABEL_STATUS = ['ijin' => 'Ijin', 'halangan' => 'Halangan', 'kabur' => 'Kabur'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function labelStatus(): string
    {
        return self::LABEL_STATUS[$this->status] ?? $this->status;
    }
}
