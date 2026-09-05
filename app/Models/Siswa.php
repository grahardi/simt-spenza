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
    public $incrementing = false; // id_member diisi manual (Nomor Induk), bukan auto-increment
    public $timestamps = false;

    protected $fillable = [
        'id_member', 'nisn', 'kelas', 'nama_lengkap', 'jenis_kelamin', 'agama',
        'alamat', 'email', 'whatsapp', 'foto_profil', 'nomer_bangku', 'id_guru_wali',
    ];

    /** Non-muslim kalau kolom agama terisi & bukan "Islam" (kosong dianggap belum diisi, bukan ditandai apapun). */
    public function bukanIslam(): bool
    {
        $agama = trim((string) $this->agama);

        return $agama !== '' && strtolower($agama) !== 'islam';
    }

    public function absensi(): HasMany
    {
        return $this->hasMany(AbsenSiswa::class, 'id_siswa', 'id_member');
    }

    /** Nomor WA yang terhubung ke siswa ini - bisa lebih dari 1 (Ayah/Ibu/Wali), maks 3. */
    public function nomorWhatsapp(): HasMany
    {
        return $this->hasMany(SiswaWhatsapp::class, 'id_siswa', 'id_member');
    }

    /**
     * Nomor WA prioritas buat notifikasi otomatis (misal konfirmasi absen
     * Sakit/Ijin) - kalau ada beberapa nomor terdaftar, IBU didahulukan.
     * Kalau tidak ada Ibu, pakai nomor pertama yang ada (Ayah/Wali).
     */
    public function nomorWaPrioritas(): ?string
    {
        $semua = $this->nomorWhatsapp;

        return $semua->firstWhere('label', 'Ibu')?->nomor ?? $semua->first()?->nomor;
    }

    public function guruWali(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru_wali', 'id_guru');
    }

    public function absenPadaTanggal(string $tanggal): ?AbsenSiswa
    {
        return $this->absensi()->whereDate('tgl_absen', $tanggal)->first();
    }

    /**
     * URL foto profil siswa. File-nya ada di storage/app/public/siswa/,
     * nama filenya persis isi kolom foto_profil.
     */
    /**
     * URL foto profil siswa. Bisa 2 format: nama file saja (lama, otomatis
     * dicari di storage/app/public/siswa/), atau path lengkap termasuk folder
     * (baru, dari Upload Foto Kelas - contoh: "foto2026/17927.jpg").
     */
    public function getFotoUrlAttribute(): ?string
    {
        if (empty($this->foto_profil)) {
            return null;
        }

        $path = str_contains($this->foto_profil, '/') ? $this->foto_profil : 'siswa/'.$this->foto_profil;

        return \Illuminate\Support\Facades\Storage::url($path);
    }

    /** Inisial 2 huruf untuk avatar default kalau belum ada foto, mis. "Ginanjar Rahardi" -> "GR". */
    public function initials(): string
    {
        $bagian = preg_split('/\s+/', trim($this->nama_lengkap));
        $depan = mb_substr($bagian[0] ?? '', 0, 1);
        $belakang = count($bagian) > 1 ? mb_substr(end($bagian), 0, 1) : '';

        $hasil = mb_strtoupper($depan.$belakang);

        return $hasil !== '' ? $hasil : '?';
    }
}
