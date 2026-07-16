<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Mengarah ke tabel `lapor_absen` asli - tempat ajuan absensi dari Admin
 * Absensi (petugas keliling kelas) mampir SEBELUM disetujui piket.
 *
 * Alurnya: Admin Absensi keliling kelas mengumpulkan surat ijin/sakit siswa,
 * lalu input jadi "ajuan" di sini (BUKAN langsung ke absen_siswa). Piket
 * lihat semua ajuan hari itu, dan ACC/Tolak satu-satu. Kalau di-ACC, baru
 * datanya pindah/tersalin ke absen_siswa (lihat AjuanAbsensiController::acc).
 * Selama belum di-ACC, siswa itu dianggap belum tercatat absen sama sekali -
 * ini penting karena status Alpha cuma bisa dideteksi lewat proses ini
 * (piket sendiri tidak bisa tahu siapa yang alpha tanpa ajuan surat masuk).
 */
class AjuanAbsensi extends Model
{
    protected $table = 'lapor_absen';
    protected $primaryKey = 'id_absen_siswa';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = ['tgl_absen', 'keterangan', 'id_siswa', 'tambahan', 'gambar', 'gambarwali', 'id_entry'];

    protected $casts = [
        'tgl_absen' => 'date',
    ];

    const KETERANGAN_LABEL = [
        's' => 'Sakit',
        'i' => 'Ijin',
        'a' => 'Alfa',
        'd' => 'Dispensasi',
    ];

    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id_member');
    }

    public function diajukanOleh(): BelongsTo
    {
        return $this->belongsTo(Member::class, 'id_entry', 'id');
    }

    public function labelKeterangan(): string
    {
        return self::KETERANGAN_LABEL[$this->keterangan] ?? '-';
    }
}
