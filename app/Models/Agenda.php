<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $table = 'agenda';

    protected $fillable = ['judul', 'tanggal_mulai', 'tanggal_selesai', 'keterangan', 'kategori', 'dibuat_oleh'];

    protected $casts = ['tanggal_mulai' => 'date', 'tanggal_selesai' => 'date'];

    const KATEGORI_LABEL = [
        'libur' => 'Libur',
        'kbm' => 'Kegiatan Belajar Mengajar',
        'ujian' => 'Ujian/Penilaian',
        'kegiatan' => 'Kegiatan Sekolah',
    ];

    const KATEGORI_WARNA = [
        'libur' => '#dc3545',
        'kbm' => '#0d6efd',
        'ujian' => '#fd7e14',
        'kegiatan' => '#198754',
    ];

    public function foto(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(AgendaFoto::class, 'id_agenda');
    }
}
