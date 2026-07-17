/**
 * Bot WhatsApp SIMT - pakai Baileys (gratis, self-hosted, tidak resmi dari Meta).
 *
 * Cara jalan:
 * 1. npm install
 * 2. Salin .env.example jadi .env, isi LARAVEL_WEBHOOK_URL & SHARED_SECRET
 * 3. node index.js  (scan QR yang muncul di terminal pakai WhatsApp di HP)
 * 4. Supaya tetap jalan setelah terminal ditutup, pakai PM2:
 *    npm install -g pm2
 *    pm2 start index.js --name simt-wa-bot
 *    pm2 save
 *    pm2 startup   (biar otomatis jalan lagi kalau server restart)
 */

require('dotenv').config();
const express = require('express');
const qrcode = require('qrcode-terminal');
const axios = require('axios');
const pino = require('pino');
const {
    default: makeWASocket,
    useMultiFileAuthState,
    DisconnectReason,
    downloadMediaMessage,
} = require('@whiskeysockets/baileys');

const LARAVEL_WEBHOOK_URL = process.env.LARAVEL_WEBHOOK_URL; // contoh: https://simt.sekolah.co.id/api/whatsapp/masuk
const SHARED_SECRET = process.env.SHARED_SECRET; // token rahasia, harus sama persis dengan di .env Laravel
const PORT = process.env.PORT || 3300;

let sockGlobal = null;

async function mulaiBot() {
    const { state, saveCreds } = await useMultiFileAuthState('sesi_auth');

    const sock = makeWASocket({
        auth: state,
        logger: pino({ level: 'silent' }), // biar log tidak berisik, ganti 'info' kalau mau debug
        printQRInTerminal: false,
    });

    sockGlobal = sock;

    sock.ev.on('creds.update', saveCreds);

    sock.ev.on('connection.update', (update) => {
        const { connection, lastDisconnect, qr } = update;

        if (qr) {
            console.log('\n=== SCAN QR CODE INI DENGAN WHATSAPP DI HP ===\n');
            qrcode.generate(qr, { small: true });
        }

        if (connection === 'close') {
            const alasan = lastDisconnect?.error?.output?.statusCode;
            const haruskonekulang = alasan !== DisconnectReason.loggedOut;
            console.log('Koneksi terputus. Sambung ulang:', haruskonekulang);
            if (haruskonekulang) mulaiBot();
        } else if (connection === 'open') {
            console.log('✅ Bot WhatsApp terhubung dan siap dipakai.');
        }
    });

    // Pesan masuk - teruskan ke Laravel
    sock.ev.on('messages.upsert', async ({ messages }) => {
        for (const msg of messages) {
            if (!msg.message || msg.key.fromMe) continue;

            const nomorPengirim = msg.key.remoteJid?.replace('@s.whatsapp.net', '');
            if (!nomorPengirim || msg.key.remoteJid.includes('@g.us')) continue; // abaikan grup

            const isGambar = !!msg.message.imageMessage;
            const teks = msg.message.conversation
                || msg.message.extendedTextMessage?.text
                || msg.message.imageMessage?.caption
                || '';

            let payload = {
                nomor: nomorPengirim,
                teks: teks.trim(),
                gambar_base64: null,
            };

            if (isGambar) {
                try {
                    const buffer = await downloadMediaMessage(msg, 'buffer', {});
                    payload.gambar_base64 = buffer.toString('base64');
                } catch (e) {
                    console.error('Gagal download gambar:', e.message);
                }
            }

            try {
                const res = await axios.post(LARAVEL_WEBHOOK_URL, payload, {
                    headers: { 'X-Bot-Secret': SHARED_SECRET },
                    timeout: 15000,
                });

                // Laravel membalas dengan { balasan: "teks yang harus dikirim ke user" }
                if (res.data?.balasan) {
                    await sock.sendMessage(msg.key.remoteJid, { text: res.data.balasan });
                }
            } catch (e) {
                console.error('Gagal teruskan ke Laravel:', e.message);
            }
        }
    });
}

mulaiBot();

// Server kecil supaya Laravel bisa MINTA bot ini KIRIM pesan (misal notifikasi setelah di-ACC piket)
const app = express();
app.use(express.json({ limit: '10mb' }));

app.post('/kirim', async (req, res) => {
    if (req.headers['x-bot-secret'] !== SHARED_SECRET) {
        return res.status(401).json({ error: 'Token tidak valid' });
    }

    const { nomor, pesan } = req.body;
    if (!nomor || !pesan) {
        return res.status(400).json({ error: 'nomor dan pesan wajib diisi' });
    }

    try {
        const jid = nomor.replace(/\D/g, '') + '@s.whatsapp.net';
        await sockGlobal.sendMessage(jid, { text: pesan });
        res.json({ status: 'terkirim' });
    } catch (e) {
        res.status(500).json({ error: e.message });
    }
});

app.listen(PORT, () => console.log(`Server kirim-pesan jalan di port ${PORT}`));
