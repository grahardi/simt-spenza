<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanSistem extends Model
{
    protected $table = 'pengaturan_sistem';

    protected $fillable = ['nomor_wa_kepsek'];

    public static function ambil(): self
    {
        return self::firstOrCreate(['id' => 1]);
    }
}
