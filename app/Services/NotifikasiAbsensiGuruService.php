<?php

namespace App\Services;

use App\Models\Guru;
use App\Models\PengaturanSistem;
use App\Models\Tugas;

class NotifikasiAbsensiGuruService
{
    /**
     * Kirim WA ke Kepala Sekolah kalau ada guru Sakit/Ijin/Dispensasi (BUKAN
     * Alfa) - baik dicatat manual oleh piket maupun lewat ajuan guru sendiri.
     * Sertakan foto surat kalau ada, dan info jumlah kelas yang tugasnya
     * sudah diupload (kalau ada - kalau belum ada sama sekali tidak usah
     * disebutkan biar tidak bikin pesan kepanjangan).
     */
    public static function kirimKeKepsek(Guru $guru, string $status, ?string $keterangan, ?string $fotoPath, string $tanggal): bool
    {
        $nomorKepsek = PengaturanSistem::ambil()->nomor_wa_kepsek;
        if (!$nomorKepsek) {
            return false;
        }

        $labelStatus = match ($status) {
            's' => 'SAKIT',
            'i' => 'IJIN',
            'd' => 'DISPENSASI',
            default => strtoupper($status),
        };

        $tanggalTampil = \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y');

        $jumlahKelasTugas = Tugas::where('idguru', $guru->id_guru)
            ->whereDate('tgl_tugas', $tanggal)
            ->distinct('kelas')
            ->count('kelas');

        $pesan = "Info Absensi Guru\n\n"
            ."Nama: {$guru->nama}\n"
            ."Status: {$labelStatus}\n"
            ."Tanggal: {$tanggalTampil}";

        if (!empty($keterangan)) {
            $pesan .= "\nKeterangan: {$keterangan}";
        }

        if ($jumlahKelasTugas > 0) {
            $pesan .= "\n\nTugas untuk {$jumlahKelasTugas} kelas sudah diupload.";
        }

        $service = new WhatsappMetaService();

        if ($fotoPath) {
            $urlFoto = \Illuminate\Support\Facades\Storage::disk('public')->url($fotoPath);

            return $service->kirimGambar($nomorKepsek, $urlFoto, $pesan);
        }

        return $service->kirimPesan($nomorKepsek, $pesan);
    }
}
