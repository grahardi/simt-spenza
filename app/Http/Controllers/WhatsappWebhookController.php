<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLog;
use App\Services\WhatsappConversationService;
use Illuminate\Http\Request;

/**
 * Endpoint yang dipanggil bot Node.js (Baileys) tiap ada pesan WA masuk.
 * Balasannya dikirim lewat field 'balasan' di response JSON, bot yang
 * meneruskan ke WhatsApp - Laravel tidak pernah kirim langsung ke bot
 * kecuali lewat WhatsappBotService (dipakai pas notif "sudah di-ACC").
 *
 * Logika percakapan (registrasi/absen/jadwal) ada di WhatsappConversationService,
 * dipakai bersama dengan WhatsappMetaWebhookController (Cloud API resmi Meta).
 */
class WhatsappWebhookController extends Controller
{
    public function masuk(Request $request, WhatsappConversationService $percakapan)
    {
        if ($request->header('X-Bot-Secret') !== config('whatsapp.secret')) {
            abort(401, 'Token tidak valid');
        }

        $nomor = preg_replace('/\D/', '', (string) $request->input('nomor'));
        $teks = trim((string) $request->input('teks'));
        $gambarBase64 = $request->input('gambar_base64');

        WhatsappLog::catat($nomor, 'masuk', $teks !== '' ? $teks : '[gambar]', 'baileys');

        $balasan = $percakapan->balas($nomor, $teks, $gambarBase64);

        WhatsappLog::catat($nomor, 'keluar', $balasan, 'baileys');

        return response()->json(['balasan' => $balasan]);
    }
}
