<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappMenu extends Model
{
    protected $table = 'whatsapp_menu';

    protected $fillable = ['kode', 'label', 'tipe', 'balasan', 'urutan', 'aktif'];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function isBawaan(): bool
    {
        return $this->tipe === 'bawaan';
    }
}
