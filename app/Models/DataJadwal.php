<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DataJadwal extends Model
{
    protected $table = 'datajadwal';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['id', 'kodejam', 'jamhari', 'hari', 'waktu', 'kodeguru', 'mapel', 'kodekelas', 'kelas'];

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'kodeguru', 'id_guru');
    }

    /**
     * Nama lengkap mata pelajaran dari singkatannya. Kalau kodenya tidak
     * dikenali, tampilkan apa adanya (bukan tebak-tebakan yang salah).
     */
    public function mapelLengkap(): string
    {
        $peta = [
            'MTK' => 'Matematika', 'MAT' => 'Matematika',
            'BIN' => 'Bahasa Indonesia',
            'BIG' => 'Bahasa Inggris',
            'IPA' => 'Ilmu Pengetahuan Alam',
            'IPS' => 'Ilmu Pengetahuan Sosial',
            'PAI' => 'Pendidikan Agama Islam',
            'PKN' => 'Pendidikan Pancasila dan Kewarganegaraan',
            'SEN' => 'Seni Budaya', 'SBD' => 'Seni Budaya',
            'PJO' => 'Pendidikan Jasmani, Olahraga, dan Kesehatan',
            'PRA' => 'Prakarya',
            'TIK' => 'Informatika',
            'BDR' => 'Bahasa Daerah', 'BD' => 'Bahasa Daerah',
            'BK' => 'Bimbingan Konseling',
        ];

        $kode = strtoupper(trim((string) $this->mapel));

        return $peta[$kode] ?? $this->mapel;
    }
}
