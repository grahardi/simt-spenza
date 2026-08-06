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

    /** Kirim gambar (pakai URL publik) + caption opsional. Balikin true/false sesuai berhasil/tidak. */
    public function kirimGambar(string $nomor, string $urlGambar, ?string $caption = null): bool
    {
        if (!$this->token() || !$this->phoneId()) {
            Log::warning('WhatsappMetaService: token/phone_id belum diatur di .env');
            \App\Models\WhatsappLog::catat($nomor, 'keluar', '[gambar] '.($caption ?? ''), 'meta', null, false, 'Token/Phone ID belum diatur di .env');

            return false;
        }

        try {
            $respon = Http::withToken($this->token())
                ->post("https://graph.facebook.com/v21.0/{$this->phoneId()}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $nomor,
                    'type' => 'image',
                    'image' => array_filter(['link' => $urlGambar, 'caption' => $caption]),
                ]);

            $wamidTerkirim = $respon->json('messages.0.id');

            if (!$respon->successful()) {
                Log::warning('WhatsappMetaService gagal kirim gambar: '.$respon->body());
            }

            \App\Models\WhatsappLog::catat(
                $nomor, 'keluar', '[gambar] '.($caption ?? ''), 'meta', $wamidTerkirim,
                $respon->successful(), $respon->successful() ? null : $respon->body()
            );

            return $respon->successful();
        } catch (\Throwable $e) {
            Log::warning('WhatsappMetaService gagal kirim gambar: '.$e->getMessage());
            \App\Models\WhatsappLog::catat($nomor, 'keluar', '[gambar] '.($caption ?? ''), 'meta', null, false, $e->getMessage());

            return false;
        }
    }

    /**
     * Kirim pesan pakai TEMPLATE resmi (sudah di-approve Meta) - ini yang
     * bisa tembus walau di luar jendela 24 jam (beda dari kirimPesan() biasa
     * yang cuma jalan kalau penerima baru saja chat ke bot). Dipakai sebagai
     * notifikasi penting yang HARUS sampai meski penerima jarang chat balik
     * (misal ke Kepala Sekolah).
     *
     * @param  string[]  $parameter  Isi tiap {{1}}, {{2}}, dst sesuai urutan di template.
     */
    public function kirimTemplate(string $nomor, string $namaTemplate, array $parameter, string $bahasa = 'id'): bool
    {
        if (!$this->token() || !$this->phoneId()) {
            Log::warning('WhatsappMetaService: token/phone_id belum diatur di .env');
            \App\Models\WhatsappLog::catat($nomor, 'keluar', '[template:'.$namaTemplate.'] '.implode(' | ', $parameter), 'meta', null, false, 'Token/Phone ID belum diatur di .env');

            return false;
        }

        try {
            $respon = Http::withToken($this->token())
                ->post("https://graph.facebook.com/v21.0/{$this->phoneId()}/messages", [
                    'messaging_product' => 'whatsapp',
                    'to' => $nomor,
                    'type' => 'template',
                    'template' => [
                        'name' => $namaTemplate,
                        'language' => ['code' => $bahasa],
                        'components' => [
                            [
                                'type' => 'body',
                                'parameters' => array_map(fn ($p) => ['type' => 'text', 'text' => (string) $p], $parameter),
                            ],
                        ],
                    ],
                ]);

            $wamidTerkirim = $respon->json('messages.0.id');

            if (!$respon->successful()) {
                Log::warning('WhatsappMetaService gagal kirim template: '.$respon->body());
            }

            \App\Models\WhatsappLog::catat(
                $nomor, 'keluar', '[template:'.$namaTemplate.'] '.implode(' | ', $parameter), 'meta', $wamidTerkirim,
                $respon->successful(), $respon->successful() ? null : $respon->body()
            );

            return $respon->successful();
        } catch (\Throwable $e) {
            Log::warning('WhatsappMetaService gagal kirim template: '.$e->getMessage());
            \App\Models\WhatsappLog::catat($nomor, 'keluar', '[template:'.$namaTemplate.'] '.implode(' | ', $parameter), 'meta', null, false, $e->getMessage());

            return false;
        }
    }

    /** Kirim pesan teks biasa. Balikin true/false sesuai berhasil/tidak. */
    public function kirimPesan(string $nomor, string $pesan): bool
    {
        if (!$this->token() || !$this->phoneId()) {
            Log::warning('WhatsappMetaService: token/phone_id belum diatur di .env');
            \App\Models\WhatsappLog::catat($nomor, 'keluar', $pesan, 'meta', null, false, 'Token/Phone ID belum diatur di .env');

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

            $wamidTerkirim = $respon->json('messages.0.id');

            if (!$respon->successful()) {
                Log::warning('WhatsappMetaService gagal kirim pesan: '.$respon->body());
            }

            \App\Models\WhatsappLog::catat(
                $nomor, 'keluar', $pesan, 'meta', $wamidTerkirim,
                $respon->successful(), $respon->successful() ? null : $respon->body()
            );

            return $respon->successful();
        } catch (\Throwable $e) {
            Log::warning('WhatsappMetaService gagal kirim pesan: '.$e->getMessage());
            \App\Models\WhatsappLog::catat($nomor, 'keluar', $pesan, 'meta', null, false, $e->getMessage());

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
