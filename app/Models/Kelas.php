<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Tabel `kelas` TIDAK terhubung lewat foreign key ke `tbl_member`.
 * Nama kelas siswa disimpan sebagai teks bebas di tbl_member.jenis_member.
 * Model ini disediakan untuk kebutuhan lain (mis. daftar kelas, wali_kelas),
 * bukan untuk relasi langsung ke Siswa.
 */
class Kelas extends Model
{
    protected $table = 'kelas';
    protected $primaryKey = 'id_kelas';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['nama_kelas', 'jumlah'];
}
