<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PengaturanDenah extends Model
{
    protected $table = 'pengaturan_denah';

    protected $fillable = ['ruang', 'tipe'];
}
