<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSurat extends Model
{
    protected $table = 'pengaturan_surat';

    protected $fillable = ['kode_baku'];

    /** Selalu ada 1 baris - ambil (atau buat kalau somehow belum ada). */
    public static function ambil(): self
    {
        return static::first() ?? static::create(['kode_baku' => '-']);
    }
}
