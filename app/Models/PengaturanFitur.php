<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PengaturanFitur extends Model
{
    protected $table = 'pengaturan_fitur';

    protected $fillable = ['role', 'fitur_key', 'aktif'];

    protected $casts = ['aktif' => 'boolean'];

    /** Key stabil dari label menu, buat disimpan/dicocokkan di database. */
    public static function keyDariLabel(string $label): string
    {
        return Str::slug($label);
    }

    /**
     * Cek apakah 1 fitur AKTIF untuk role tertentu. Default AKTIF kalau belum
     * pernah diatur sama sekali (tidak perlu hardcode daftar - kalau tidak
     * ada baris di database, berarti belum pernah dinonaktifkan).
     */
    public static function aktifUntuk(string $role, string $label): bool
    {
        static $cache = [];
        if (!isset($cache[$role])) {
            $cache[$role] = static::where('role', $role)->where('aktif', false)->pluck('fitur_key')->all();
        }

        return !in_array(static::keyDariLabel($label), $cache[$role], true);
    }
}
