<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PelanggaranKeagamaanTindakan extends Model
{
    protected $table = 'pelanggaran_keagamaan_tindakan';

    protected $fillable = ['id_siswa', 'jumlah_kabur_saat_tindak', 'keterangan', 'ditindak_oleh'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
