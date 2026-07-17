<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AjuanWhatsapp extends Model
{
    protected $table = 'ajuan_whatsapp';
    public $timestamps = false;

    protected $fillable = ['nomor_wa', 'id_siswa', 'jenis', 'foto_surat', 'status', 'diproses_at', 'diproses_oleh'];

    protected $casts = [
        'created_at' => 'datetime',
        'diproses_at' => 'datetime',
    ];

    const LABEL_JENIS = ['s' => 'Sakit', 'i' => 'Ijin'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function labelJenis(): string
    {
        return self::LABEL_JENIS[$this->jenis] ?? $this->jenis;
    }
}
