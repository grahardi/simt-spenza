<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * Mengarah ke tabel `member` (tabel login asli sistem lama).
 * BUKAN tabel `user`/`username`/`username_profiles` - tabel-tabel itu
 * legacy dan tidak dipakai di kode manapun (sudah dicek, tidak ada query
 * yang mengaksesnya), sengaja diabaikan sesuai arahan.
 *
 * Kolom login lama:
 * - `user`     : nomor ID login (BUKAN email/username teks)
 * - `password` : dulu plain text, sekarang bertahap di-hash lewat
 *                LegacyPasswordEloquentUserProvider
 * - flag role  : admin, walikelas, tatib, bk, piket, guru, keagamaan,
 *                kebersihan, kepsek, adminsoal (masing-masing 0/1) -
 *                KECUALI `piket`, lihat isPiketToday().
 */
class Member extends Authenticatable
{
    protected $table = 'member';
    protected $primaryKey = 'id';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'id', 'user', 'password', 'nama', 'jabatan', 'admin', 'walikelas',
        'tatib', 'bk', 'piket', 'guru', 'keagamaan', 'kebersihan',
        'kepsek', 'id_guru', 'id_karyawan', 'foto', 'panggilan', 'adminsoal', 'tata_usaha', 'uks', 'kesiswaan',
        'wajib_ganti_password', 'last_login_at',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'wajib_ganti_password' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    /** Field yang dipakai sebagai "username" saat login (nomor ID, bukan email). */
    public function username(): string
    {
        return 'user';
    }

    public function dataGuru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    public function karyawan(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Karyawan::class, 'id_karyawan', 'id_karyawan');
    }

    /**
     * Nama hari dalam Bahasa Indonesia (HURUF BESAR), memakai waktu Jakarta -
     * pengganti include/besar.php lama.
     */
    public static function namaHariJakartaHuruBesar(): string
    {
        $peta = [
            'Sunday' => 'MINGGU', 'Monday' => 'SENIN', 'Tuesday' => 'SELASA',
            'Wednesday' => 'RABU', 'Thursday' => 'KAMIS', 'Friday' => 'JUMAT',
            'Saturday' => 'SABTU',
        ];

        return $peta[Carbon::now('Asia/Jakarta')->format('l')];
    }

    /**
     * Kolom `piket` di sistem lama punya 2 arti berbeda:
     * - '1'                -> piket admin, aktif SETIAP hari
     * - 'SENIN'/'SELASA'/dst -> piket kelas/harian, HANYA aktif di hari itu
     * Pengecekan HARUS pakai waktu Jakarta (bukan waktu server sandbox/hosting).
     */
    public function isPiketToday(): bool
    {
        $nilai = trim((string) $this->piket);

        if ($nilai === '') {
            return false;
        }

        if ($nilai === '1') {
            return true;
        }

        return strtoupper($nilai) === self::namaHariJakartaHuruBesar();
    }

    /**
     * Daftar role yang dimiliki, dipetakan dari kolom flag lama.
     * `piket` diperlakukan khusus lewat isPiketToday() karena isinya bisa
     * berupa nama hari, bukan cuma 0/1 seperti kolom role lainnya.
     */
    public function roles(): array
    {
        $map = [
            'admin' => 'admin',
            'walikelas' => 'walikelas',
            'tatib' => 'tatib',
            'bk' => 'bk',
            'guru' => 'guru',
            'keagamaan' => 'keagamaan',
            'kebersihan' => 'kebersihan',
            'kepsek' => 'kepsek',
            'adminsoal' => 'adminsoal',
            'tata_usaha' => 'tata_usaha',
            'uks' => 'uks',
            'kesiswaan' => 'kesiswaan',
        ];

        $roles = [];
        foreach ($map as $kolom => $role) {
            if (!empty($this->{$kolom})) {
                $roles[] = $role;
            }
        }

        if ($this->isPiketToday()) {
            $roles[] = 'piket';
        }

        return $roles;
    }

    public function hasRole(string $role): bool
    {
        if ($role === 'piket') {
            return $this->isPiketToday();
        }

        if ($role === 'superadmin') {
            return strtolower(trim((string) $this->jabatan)) === 'superadmin';
        }

        return in_array($role, $this->roles(), true);
    }

    /**
     * Kolom `id` di tabel member BUKAN auto-increment (sama seperti
     * datajadwal) - harus diisi manual tiap kali bikin akun baru.
     */
    public static function idBerikutnya(): int
    {
        return (int) (static::max('id') ?? 0) + 1;
    }
}
