<?php

return [
    // URL server bot Node.js (Baileys), contoh: http://127.0.0.1:3300
    'url' => env('WA_BOT_URL'),

    // Token rahasia, HARUS sama persis dengan SHARED_SECRET di .env bot Node.js
    'secret' => env('WA_BOT_SECRET'),
];
