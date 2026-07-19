<?php

namespace App\Http\Controllers;

use App\Models\WhatsappLog;
use App\Services\WhatsappConversationService;
use App\Services\WhatsappMetaService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Webhook untuk WhatsApp Cloud API RESMI (Meta) - terpisah total dari
 * WhatsappWebhookController yang dipakai bot Baileys. Dua-duanya pakai
 * "otak" yang sama (WhatsappConversationService), cuma beda cara terima
 * & kirim pesannya.
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
     * Terima pesan masuk dari Meta. Beda dari Baileys: balasannya TIDAK
     * dikembalikan lewat response body (Meta cuma butuh 200 OK), tapi
     * harus dikirim AKTIF lewat WhatsappMetaService::kirimPesan().
     *
     * PENTING: Meta bisa mengirim ULANG event webhook yang sama kalau
     * server kita telat/timeout membalas 200 - tanpa pengecekan wamid,
     * ini bisa bikin 1 pesan diproses & dibalas berkali-kali (loop).
     */
    public function masuk(Request $request, WhatsappConversationService $percakapan, WhatsappMetaService $metaBot)
    {
        $pesan = $request->input('entry.0.changes.0.value.messages.0');

        // Bisa juga webhook status update (delivered/read) yang tidak ada
        // 'messages', cuma 'statuses' - abaikan saja, bukan pesan masuk.
        if (!$pesan) {
            return response()->json(['status' => 'ok']);
        }

        $wamid = $pesan['id'] ?? null;

        if (WhatsappLog::sudahDiproses($wamid)) {
            Log::info('Webhook WhatsApp Meta: pesan '.$wamid.' sudah pernah diproses, dilewati (cegah duplikat/retry).');

            return response()->json(['status' => 'ok']);
        }

        $nomor = preg_replace('/\D/', '', (string) ($pesan['from'] ?? ''));
        $teks = trim((string) ($pesan['text']['body'] ?? ''));
        $gambarBase64 = null;

        if (isset($pesan['image']['id'])) {
            $gambarBase64 = $metaBot->ambilMediaBase64($pesan['image']['id']);
        }

        if ($nomor === '') {
            Log::warning('Webhook WhatsApp Meta: nomor pengirim kosong', $request->all());

            return response()->json(['status' => 'ok']);
        }

        WhatsappLog::catat($nomor, 'masuk', $teks !== '' ? $teks : '[gambar]', 'meta', $wamid);

        $balasan = $percakapan->balas($nomor, $teks, $gambarBase64);

        $metaBot->kirimPesan($nomor, $balasan);

        return response()->json(['status' => 'ok']);
    }
}
