<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KategoriSurat extends Model
{
    protected $table = 'kategori_surat';

    protected $fillable = ['kode', 'nama', 'keterangan'];

    public function suratKeluar(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'id_kategori_surat');
    }
}
