<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SoalUpload extends Model
{
    protected $table = 'soal_upload';

    protected $fillable = ['kelas', 'mapel', 'tipe', 'path', 'id_guru'];

    const LABEL_TIPE = ['aplikasi' => 'Format Aplikasi', 'cetak' => 'Format Cetak'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }
}
