<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BerkasLain extends Model
{
    protected $table = 'berkas_lain';

    protected $fillable = ['keterangan', 'nama_file_asli', 'path', 'diupload_oleh'];
}
