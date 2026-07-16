<?php

use App\Http\Controllers\Auth\MemberLoginController;
use App\Http\Controllers\AbsensiSiswaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Pengganti index.php lama (router if/elseif ~470 baris untuk 90+ halaman)
|--------------------------------------------------------------------------
| Login pakai guard `member` (custom, lihat config/auth.php & Model Member) -
| bukan guard `web` bawaan Breeze, karena tabel login lama beda struktur
| (nomor ID, bukan email).
*/

Route::get('/login', [MemberLoginController::class, 'create'])->name('login');
Route::post('/login', [MemberLoginController::class, 'store'])->name('login.store');
Route::post('/logout', [MemberLoginController::class, 'destroy'])->name('logout');

Route::middleware('auth:member')->group(function () {

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profil', function () {
        return view('profil');
    })->name('profil');

    // Modul Absensi Siswa - semua role yang dulu bisa akses absenjelas.php
    Route::prefix('absensi')->name('absensi.')->middleware('role:guru,walikelas,kepsek,piket,admin')->group(function () {
        Route::get('/', [AbsensiSiswaController::class, 'index'])->name('index');
    });

    // Placeholder modul lain, supaya link di bottom-nav tidak 404 dulu.
    Route::get('/jadwal', fn () => view('placeholder', ['judul' => 'Jadwal Pelajaran']))->name('jadwal.index');
    Route::get('/tugas', fn () => view('placeholder', ['judul' => 'Tugas Siswa']))->name('tugas.index');

    // Route generik untuk semua menu panel_*.php yang belum dimigrasi satu-satu.
    // Begitu modul aslinya jadi, ganti pemanggilannya di dashboard.blade.php
    // dari route('modul', 'slug-ini') ke route controller yang sesungguhnya.
    Route::get('/modul/{slug}', function (string $slug) {
        return view('placeholder', ['judul' => ucwords(str_replace('-', ' ', $slug))]);
    })->name('modul');
});
