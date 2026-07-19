<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_log';

    public $timestamps = false;

    protected $fillable = ['nomor', 'arah', 'teks', 'sumber', 'wamid', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    public static function catat(string $nomor, string $arah, ?string $teks, string $sumber = 'meta', ?string $wamid = null): void
    {
        static::create([
            'nomor' => $nomor,
            'arah' => $arah,
            'teks' => $teks,
            'sumber' => $sumber,
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
