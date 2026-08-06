<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappLog extends Model
{
    protected $table = 'whatsapp_log';

    public $timestamps = false;

    protected $fillable = ['nomor', 'arah', 'teks', 'sumber', 'berhasil', 'detail_error', 'status_pengiriman', 'wamid', 'created_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'berhasil' => 'boolean',
    ];

    const LABEL_STATUS_PENGIRIMAN = [
        'sent' => 'Terkirim ke server WhatsApp',
        'delivered' => 'Sampai di HP penerima',
        'read' => 'Sudah dibaca',
        'failed' => 'Gagal - tidak sampai',
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

    /** Update status pengiriman SEBENARNYA (sent/delivered/read/failed) - dipanggil dari webhook status Meta. */
    public static function catatStatusUpdate(string $wamid, string $status, ?string $detailErrorTambahan = null): void
    {
        $log = static::where('wamid', $wamid)->where('arah', 'keluar')->first();
        if (!$log) {
            return;
        }

        $data = ['status_pengiriman' => $status];
        if ($status === 'failed' && $detailErrorTambahan) {
            $data['detail_error'] = trim(($log->detail_error ? $log->detail_error.' | ' : '').$detailErrorTambahan);
            $data['berhasil'] = false;
        }
        $log->update($data);
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
