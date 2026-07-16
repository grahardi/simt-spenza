<?php

use App\Http\Controllers\AbsensiSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pengganti index.php lama (router if/elseif ~470 baris untuk 90+ halaman)
|--------------------------------------------------------------------------
| Pola: setiap "$_GET['halaman'] == 'xxx'" di kode lama menjadi satu route
| bernama di sini, dikelompokkan per modul, dengan middleware role yang
| jelas (menggantikan panel_*.php yang dulu terpisah per role).
|
| Modul lain (kebersihan, tatib, bimbingan, keagamaan, rpp, smart, surat,
| dst) menyusul dengan pola yang identis begitu masing-masing controller
| dibuat. Struktur di bawah ini sudah final untuk dipakai sebagai acuan.
*/

Route::middleware('auth')->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profil', function () {
        return view('profil');
    })->name('profil');

    // Modul Absensi Siswa (contoh modul yang sudah dimigrasi penuh)
    Route::prefix('absensi')->name('absensi.')->group(function () {
        Route::get('/', [AbsensiSiswaController::class, 'index'])->name('index');
        // ->middleware('role:guru,walikelas,kepsek,piket') // aktifkan setelah spatie/laravel-permission dipasang
    });

    // Placeholder modul lain, supaya link di bottom-nav tidak 404 dulu.
    // Ganti Closure ini dengan controller sungguhan saat modul terkait dimigrasi.
    Route::get('/jadwal', fn () => view('placeholder', ['judul' => 'Jadwal Pelajaran']))->name('jadwal.index');
    Route::get('/tugas', fn () => view('placeholder', ['judul' => 'Tugas Siswa']))->name('tugas.index');
});

require __DIR__.'/auth.php'; // disediakan oleh Laravel Breeze/Fortify saat instalasi
