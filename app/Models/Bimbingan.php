<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bimbingan extends Model
{
    protected $table = 'bimbingan';
    protected $primaryKey = 'id_bk';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'id_siswa', 'tgl_bimbingan', 'kategori', 'Keterangan',
        'Tindakan', 'gambar', 'warning', 'id_entry', 'guru_bk',
    ];

    protected $casts = [
        'tgl_bimbingan' => 'date',
    ];

    const KATEGORI = ['Pendampingan', 'Verifikasi', 'Pelanggaran', 'Lainnya'];
    const TINDAKAN = ['Tidak Ada', 'Notifikasi', 'Peringatan', 'Tindakan'];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function pelapor(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_entry', 'id');
    }
}
