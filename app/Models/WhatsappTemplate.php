<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappTemplate extends Model
{
    protected $table = 'whatsapp_template';

    protected $fillable = ['kode', 'keterangan', 'teks'];

    /**
     * Ambil teks template dari database, ganti placeholder {nama}, {kelas}, dst.
     * Kalau kode-nya entah kenapa tidak ada di database, kembalikan string
     * kosong yang jelas kelihatan (bukan diam-diam null/error) supaya gampang
     * ketahuan kalau ada yang perlu di-seed ulang.
     */
    public static function get(string $kode, array $ganti = []): string
    {
        $teks = static::where('kode', $kode)->value('teks');

        if ($teks === null) {
            return "[Teks bot '{$kode}' belum diatur - hubungi admin]";
        }

        foreach ($ganti as $cari => $isi) {
            $teks = str_replace('{'.$cari.'}', (string) $isi, $teks);
        }

        return $teks;
    }
}
