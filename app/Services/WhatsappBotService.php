<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappBotService
{
    public function kirimPesan(string $nomor, string $pesan): bool
    {
        $url = config('whatsapp.url');
        $secret = config('whatsapp.secret');

        if (!$url || !$secret) {
            Log::warning('WhatsappBotService: WA_BOT_URL/WA_BOT_SECRET belum diset di .env');
            return false;
        }

        try {
            $response = Http::withHeaders(['X-Bot-Secret' => $secret])
                ->timeout(10)
                ->post(rtrim($url, '/').'/kirim', [
                    'nomor' => $nomor,
                    'pesan' => $pesan,
                ]);

            return $response->successful();
        } catch (\Throwable $e) {
            Log::error('WhatsappBotService gagal kirim pesan: '.$e->getMessage());
            return false;
        }
    }
}
