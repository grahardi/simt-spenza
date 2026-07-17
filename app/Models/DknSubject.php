<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DknSubject extends Model
{
    protected $table = 'dkn_subjects';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['kode_mapel', 'nama_mapel'];
}
