<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Mengarah ke tabel `tbl_member` (BUKAN tabel `siswa`).
 * Terkonfirmasi dari absen26.sql: `tbl_member` berisi data siswa aktif/nyata
 * (foto, whatsapp, jenis_member sebagai nama kelas). Tabel `siswa` di database
 * adalah tabel lama yang datanya tidak lengkap/tidak dipakai lagi.
 */
class Siswa extends Model
{
    protected $table = 'tbl_member';
    protected $primaryKey = 'id_member';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'tanggal_gabung', 'jenis_member', 'nama_lengkap', 'jenis_kelamin',
        'alamat', 'email', 'whatsapp', 'foto_profil', 'nomer_bangku',
    ];

    /**
     * Nama kelas di sistem lama disimpan sebagai teks bebas di jenis_member
     * (contoh: "9 - A"), BUKAN foreign key ke tabel `kelas`. Accessor ini
     * dipakai supaya kode di controller/view tetap bisa memanggil ->kelas
     * tanpa tahu detail ini.
     */
    public function getKelasAttribute(): string
    {
        return $this->jenis_member;
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsenSiswa::class, 'id_siswa', 'id_member');
    }

    public function absenPadaTanggal(string $tanggal): ?AbsenSiswa
    {
        return $this->absensi()->whereDate('tgl_absen', $tanggal)->first();
    }
}
