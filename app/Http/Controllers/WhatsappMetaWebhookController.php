<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook untuk WhatsApp Cloud API RESMI (Meta) - terpisah total dari
 * WhatsappWebhookController yang dipakai bot Baileys. Dua-duanya bisa
 * jalan berdampingan (nomor beda) selama masa transisi/percobaan.
 */
class WhatsappMetaWebhookController extends Controller
{
    /**
     * Verifikasi webhook - dipanggil Meta lewat GET saat pertama kali
     * pasang Callback URL di dashboard. Wajib balas persis nilai
     * hub.challenge sebagai teks polos kalau verify_token cocok.
     */
    public function verify(Request $request)
    {
        $mode = $request->query('hub_mode');
        $token = $request->query('hub_verify_token');
        $challenge = $request->query('hub_challenge');

        if ($mode === 'subscribe' && $token === config('services.whatsapp_meta.verify_token')) {
            return response($challenge, 200);
        }

        return response('Verifikasi gagal - token tidak cocok', 403);
    }

    /**
     * Terima pesan/status masuk dari Meta. Untuk sekarang baru dicatat ke
     * log dulu supaya bisa dites koneksinya - alur registrasi/absen yang
     * sudah ada di WhatsappWebhookController (Baileys) bisa disambungkan
     * ke sini belakangan begitu koneksi Meta-nya sudah pasti jalan.
     */
    public function masuk(Request $request)
    {
        Log::info('Webhook WhatsApp Meta diterima', $request->all());

        return response()->json(['status' => 'ok']);
    }
}
