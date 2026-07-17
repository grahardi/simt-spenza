<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mengarah ke tabel `datasiswa` (hasil rename dari `tbl_member`).
 * Kolom `nisn` (dulu `tanggal_gabung`) dan `kelas` (dulu `jenis_member`)
 * sudah diluruskan namanya lewat migration rename_tbl_member_to_datasiswa.
 */
class Siswa extends Model
{
    protected $table = 'datasiswa';
    protected $primaryKey = 'id_member';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'nisn', 'kelas', 'nama_lengkap', 'jenis_kelamin',
        'alamat', 'email', 'whatsapp', 'foto_profil', 'nomer_bangku',
    ];

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsenSiswa::class, 'id_siswa', 'id_member');
    }

    public function absenPadaTanggal(string $tanggal): ?AbsenSiswa
    {
        return $this->absensi()->whereDate('tgl_absen', $tanggal)->first();
    }

    /**
     * URL foto profil siswa. File-nya ada di storage/app/public/siswa/,
     * nama filenya persis isi kolom foto_profil.
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (empty($this->foto_profil)) {
            return null;
        }

        return \Illuminate\Support\Facades\Storage::url('siswa/'.$this->foto_profil);
    }
}
