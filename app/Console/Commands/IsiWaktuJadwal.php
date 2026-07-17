<?php

namespace App\Console\Commands;

use App\Models\DataJadwal;
use Illuminate\Console\Command;

class IsiWaktuJadwal extends Command
{
    protected $signature = 'jadwal:isi-waktu';

    protected $description = 'Isi kolom waktu di datajadwal berdasarkan referensi jam pelajaran per hari (dari jjm_jadwal.xlsx)';

    public function handle(): int
    {
        $referensi = require database_path('data/waktu_pelajaran.php');

        $diisi = 0;
        $dilewati = 0;

        DataJadwal::chunk(200, function ($rows) use ($referensi, &$diisi, &$dilewati) {
            foreach ($rows as $row) {
                $waktu = $referensi[$row->hari][(int) $row->jamhari] ?? null;

                if ($waktu === null) {
                    $dilewati++;
                    continue;
                }

                $row->update(['waktu' => $waktu]);
                $diisi++;
            }
        });

        $this->info("Selesai: {$diisi} baris jadwal diisi waktunya, {$dilewati} dilewati (jam khusus seperti 'x' tidak ada di referensi).");

        return self::SUCCESS;
    }
}
