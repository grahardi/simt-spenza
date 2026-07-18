<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WhatsappSesi extends Model
{
    protected $table = 'whatsapp_sesi';
    public $timestamps = false;

    protected $fillable = ['nomor', 'langkah', 'id_siswa_dipilih', 'jenis_dipilih', 'id_siswa_calon_registrasi', 'foto_sementara', 'updated_at'];

    public function reset(): void
    {
        $this->update([
            'langkah' => 'menu',
            'id_siswa_dipilih' => null,
            'jenis_dipilih' => null,
            'id_siswa_calon_registrasi' => null,
            'foto_sementara' => null,
        ]);
    }
}
