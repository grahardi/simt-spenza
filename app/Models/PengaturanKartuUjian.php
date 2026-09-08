<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanKartuUjian extends Model
{
    protected $table = 'pengaturan_kartu_ujian';

    protected $fillable = ['judul', 'tanggal'];

    protected $casts = ['tanggal' => 'date'];

    public static function ambil(): self
    {
        return self::firstOrCreate(['id' => 1], [
            'judul' => 'Kartu Peserta Sumatif Akhir Jenjang',
            'tanggal' => now()->toDateString(),
        ]);
    }
}
