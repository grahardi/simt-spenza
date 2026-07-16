<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
 *                kebersihan, kepsek, adminsoal (masing-masing 0/1)
 */
class Member extends Authenticatable
{
    protected $table = 'member';
    protected $primaryKey = 'id';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'user', 'password', 'nama', 'jabatan', 'admin', 'walikelas',
        'tatib', 'bk', 'piket', 'guru', 'keagamaan', 'kebersihan',
        'kepsek', 'id_guru', 'foto', 'panggilan', 'adminsoal',
    ];

    protected $hidden = ['password'];

    /** Field yang dipakai sebagai "username" saat login (nomor ID, bukan email). */
    public function username(): string
    {
        return 'user';
    }

    public function guru(): BelongsTo
    {
        return $this->belongsTo(Guru::class, 'id_guru', 'id_guru');
    }

    /**
     * Daftar role yang dipetakan dari kolom flag lama ke nama role yang
     * dipakai middleware (lihat EnsureHasRole).
     */
    public function roles(): array
    {
        $map = [
            'admin' => 'admin',
            'walikelas' => 'walikelas',
            'tatib' => 'tatib',
            'bk' => 'bk',
            'piket' => 'piket',
            'guru' => 'guru',
            'keagamaan' => 'keagamaan',
            'kebersihan' => 'kebersihan',
            'kepsek' => 'kepsek',
            'adminsoal' => 'adminsoal',
        ];

        $roles = [];
        foreach ($map as $kolom => $role) {
            if (!empty($this->{$kolom})) {
                $roles[] = $role;
            }
        }

        return $roles;
    }

    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles(), true);
    }
}
