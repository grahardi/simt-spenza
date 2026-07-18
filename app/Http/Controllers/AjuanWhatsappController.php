<?php

namespace App\Http\Controllers;

use App\Models\AbsenSiswa;
use App\Models\AjuanWhatsapp;
use App\Services\WhatsappBotService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanWhatsappController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->input('status', 'menunggu');

        $ajuan = AjuanWhatsapp::with('siswa')
            ->where('status', $status)
            ->orderByDesc('created_at')
            ->paginate(20)
            ->withQueryString();

        return view('ajuan-whatsapp.index', compact('ajuan', 'status'));
    }

    public function acc(AjuanWhatsapp $ajuan, WhatsappBotService $bot)
    {
        AbsenSiswa::updateOrCreate(
            ['id_siswa' => $ajuan->id_siswa, 'tgl_absen' => $ajuan->created_at->toDateString()],
            ['keterangan' => $ajuan->jenis, 'gambar' => $ajuan->foto_surat]
        );

        $ajuan->update([
            'status' => 'disetujui',
            'diproses_at' => now(),
            'diproses_oleh' => Auth::guard('member')->id(),
        ]);

        \App\Models\LogAktivitas::catat(
            'absensi',
            (Auth::guard('member')->user()->nama ?? 'Piket').' menyetujui ajuan WhatsApp '.($ajuan->siswa->nama_lengkap ?? '').' ('.$ajuan->labelJenis().').'
        );

        $labelJenis = $ajuan->labelJenis();
        $terkirim = $bot->kirimPesan(
            $ajuan->nomor_wa,
            "\xE2\x9C\x85 Ajuan *{$labelJenis}* untuk ananda *{$ajuan->siswa->nama_lengkap}* pada {$ajuan->created_at->translatedFormat('d F Y')} sudah *diproses/disetujui*. Terima kasih."
        );

        return back()->with('status', $terkirim
            ? 'Ajuan berhasil disetujui dan notifikasi sudah dikirim ke wali murid.'
            : 'Ajuan berhasil disetujui, TAPI notifikasi WhatsApp ke wali murid GAGAL terkirim (cek pengaturan bot / log sistem).');
    }

    public function tolak(Request $request, AjuanWhatsapp $ajuan, WhatsappBotService $bot)
    {
        $ajuan->update([
            'status' => 'ditolak',
            'diproses_at' => now(),
            'diproses_oleh' => Auth::guard('member')->id(),
        ]);

        $labelJenis = $ajuan->labelJenis();
        $terkirim = $bot->kirimPesan(
            $ajuan->nomor_wa,
            "Mohon maaf, ajuan *{$labelJenis}* untuk ananda *{$ajuan->siswa->nama_lengkap}* pada {$ajuan->created_at->translatedFormat('d F Y')} *belum bisa disetujui*. Silakan hubungi pihak sekolah untuk info lebih lanjut."
        );

        return back()->with('status', $terkirim
            ? 'Ajuan ditolak dan notifikasi sudah dikirim ke wali murid.'
            : 'Ajuan ditolak, TAPI notifikasi WhatsApp ke wali murid GAGAL terkirim (cek pengaturan bot / log sistem).');
    }
}
