<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_log';

    public $timestamps = false;

    protected $fillable = ['nomor', 'arah', 'teks', 'sumber', 'berhasil', 'detail_error', 'wamid', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'berhasil' => 'boolean',
    ];

    public static function catat(string $nomor, string $arah, ?string $teks, string $sumber = 'meta', ?string $wamid = null, ?bool $berhasil = null, ?string $detailError = null): void
    {
        static::create([
            'nomor' => $nomor,
            'arah' => $arah,
            'teks' => $teks,
            'sumber' => $sumber,
            'berhasil' => $berhasil,
            'detail_error' => $detailError,
            'wamid' => $wamid,
            'created_at' => now(),
        ]);
    }

    /** Cek apakah pesan dengan wamid ini sudah pernah diproses - cegah dobel kalau Meta retry webhook. */
    public static function sudahDiproses(?string $wamid): bool
    {
        if (!$wamid) {
            return false;
        }

        return static::where('wamid', $wamid)->exists();
    }
}
