<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Kirim pesan & ambil media lewat WhatsApp Cloud API resmi (Meta) -
 * beda dari WhatsappBotService yang manggil bot Baileys sendiri.
 */
class WhatsappMetaService
{
    private function token(): ?string
    {
        return config('services.whatsapp_meta.token');
    }

    private function phoneId(): ?string
    {
        return config('services.whatsapp_meta.phone_id');
    }

    /** Kirim pesan teks biasa. Balikin true/false sesuai berhasil/tidak. */
    public function kirimPesan(string $nomor, string $pesan): bool
    {
        if (!$this->token() || !$this->phoneId()) {
            Log::warning('WhatsappMetaService: token/phone_id belum diatur di .env');

            return false;
        }

        try {
            $respon = Http::withToken($this->token())
                ->post("https://graph.facebook.com/v21.0/{$this->phoneId()}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $nomor,
                    'type' => 'text',
                    'text' => ['body' => $pesan],
                ]);

            if (!$respon->successful()) {
                Log::warning('WhatsappMetaService gagal kirim pesan: '.$respon->body());
            }

            \App\Models\WhatsappLog::catat($nomor, 'keluar', $pesan, 'meta');

            return $respon->successful();
        } catch (\Throwable $e) {
            Log::warning('WhatsappMetaService gagal kirim pesan: '.$e->getMessage());

            return false;
        }
    }

    /**
     * Ambil isi media (foto) berdasar Media ID yang dikirim Meta di webhook.
     * Beda dari Baileys yang langsung kasih base64 - di Meta harus 2 langkah:
     * (1) GET info media buat dapat URL sementara, (2) download URL itu.
     * Balikin base64 (format sama seperti Baileys) supaya bisa dipakai
     * bareng WhatsappConversationService tanpa ubah kode di sana.
     */
    public function ambilMediaBase64(string $mediaId): ?string
    {
        if (!$this->token()) {
            return null;
        }

        try {
            $info = Http::withToken($this->token())
                ->get("https://graph.facebook.com/v21.0/{$mediaId}");

            if (!$info->successful() || !$info->json('url')) {
                Log::warning('WhatsappMetaService gagal ambil info media: '.$info->body());

                return null;
            }

            $file = Http::withToken($this->token())->get($info->json('url'));

            if (!$file->successful()) {
                Log::warning('WhatsappMetaService gagal download media.');

                return null;
            }

            return base64_encode($file->body());
        } catch (\Throwable $e) {
            Log::warning('WhatsappMetaService gagal ambil media: '.$e->getMessage());

            return null;
        }
    }
}
