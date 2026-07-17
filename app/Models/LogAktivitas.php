<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class LogAktivitas extends Model
{
    protected $table = 'log_aktivitas';
    public $timestamps = false;

    protected $fillable = ['id_member', 'kategori', 'aksi', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    const KATEGORI = ['absensi', 'pelanggaran', 'keterlambatan', 'sistem', 'lainnya'];

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_member', 'id');
    }

    /**
     * Helper untuk mencatat 1 baris log dari mana saja di aplikasi.
     * Contoh: LogAktivitas::catat('absensi', 'menambah absen Fania Zahra jadi Sakit');
     */
    public static function catat(string $kategori, string $aksi): void
    {
        static::create([
            'id_member' => Auth::guard('member')->id(),
            'kategori' => $kategori,
            'aksi' => $aksi,
            'created_at' => now(),
        ]);
    }
}
