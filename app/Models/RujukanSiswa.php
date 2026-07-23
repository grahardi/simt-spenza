<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RujukanSiswa extends Model
{
    protected $table = 'rujukan_siswa';

    protected $fillable = [
        'id_siswa', 'jenis', 'alasan', 'status', 'tindak_lanjut',
        'catatan_tindak_lanjut', 'dilaporkan_oleh', 'ditindak_oleh', 'ditindak_at',
    ];

    protected $casts = ['ditindak_at' => 'datetime'];

    const TINDAK_LABEL = [
        'konfirmasi' => 'Konfirmasi Saja',
        'hubungi_ortu' => 'Hubungi Orang Tua',
        'ajukan_bk' => 'Diajukan ke BK',
        'ajukan_tatib' => 'Diajukan ke Tatib',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function labelTindakLanjut(): ?string
    {
        return self::TINDAK_LABEL[$this->tindak_lanjut] ?? null;
    }
}
