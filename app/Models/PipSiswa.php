<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PipSiswa extends Model
{
    protected $table = 'pip_siswa';

    protected $fillable = [
        'id_siswa', 'nisn', 'nama_pd', 'kelas_excel',
        'nominal', 'no_rekening', 'tahap_id', 'nomor_sk', 'tanggal_sk', 'nama_rekening',
        'tanggal_cair', 'status_cair', 'no_kip', 'no_kks', 'no_kps', 'virtual_acc',
        'nama_kartu', 'semester_id', 'layak_pip', 'keterangan_pencairan',
        'confirmation_text', 'tahap_keterangan', 'nama_pengusul',
    ];

    protected $casts = [
        'tanggal_sk' => 'date',
        'tanggal_cair' => 'date',
        'nominal' => 'decimal:2',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }
}
