<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Cek siswa Alpha & sering tidak masuk setiap hari jam 15:00 (setelah jam
// pelajaran & piket biasanya sudah selesai input absensi harian).
Schedule::command('warning:cek-otomatis')->dailyAt('15:00');
