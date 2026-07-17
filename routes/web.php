<?php

use App\Http\Controllers\Auth\MemberLoginController;
use App\Http\Controllers\AbsensiSiswaController;
use App\Http\Controllers\AjuanAbsensiController;
use App\Http\Controllers\AktivitasKelasController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\KeagamaanController;
use App\Http\Controllers\KebersihanController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TatibController;
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
        Route::get('/foto/{absen}', [AbsensiSiswaController::class, 'foto'])->name('foto');

        // Isi absensi manual & catat terlambat - KHUSUS piket (admin tidak boleh ubah absensi resmi)
        Route::middleware('role:piket')->group(function () {
            Route::get('/isi', [AbsensiSiswaController::class, 'isi'])->name('isi');
            Route::post('/tandai/{siswa}', [AbsensiSiswaController::class, 'tandai'])->name('tandai');
            Route::delete('/hapus/{absen}', [AbsensiSiswaController::class, 'hapus'])->name('hapus');
            Route::post('/telat/{siswa}', [AbsensiSiswaController::class, 'telat'])->name('telat');
            Route::get('/telat', [AbsensiSiswaController::class, 'listTelat'])->name('telat.list');
        });
    });

    // Data Master Siswa - pengganti daftarnama.php, siswatambah.php, prosessiswa.php
    Route::resource('siswa', SiswaController::class)
        ->middleware('role:guru,walikelas,kepsek,admin,piket');

    // Data Master Guru - pengganti gurutambah.php, arsipguru.php
    Route::resource('guru', GuruController::class)
        ->middleware('role:kepsek,admin,piket');

    // Jadwal Mengajar - khusus guru, jadwal hari ini
    Route::get('/jadwal-mengajar', [JadwalGuruController::class, 'index'])
        ->name('jadwal-mengajar')
        ->middleware('role:guru');

    // Aktivitas Kelas - khusus wali kelas, rekap absensi kelas yang diampu
    Route::get('/aktivitas-kelas', [AktivitasKelasController::class, 'index'])
        ->name('aktivitas-kelas')
        ->middleware('role:walikelas');

    // Kebersihan Kelas - guru (siapa saja yang mengajar) bisa lapor kelas kotor,
    // role kebersihan yang tindak lanjuti & lihat galeri.
    Route::prefix('kebersihan')->name('kebersihan.')->group(function () {
        Route::get('/kelas', [KebersihanController::class, 'kelasGrid'])->name('kelas-grid');
        Route::get('/lapor/{kelas}', [KebersihanController::class, 'lapor'])->name('lapor');
        Route::post('/lapor/{kelas}', [KebersihanController::class, 'simpan'])->name('simpan');

        Route::middleware('role:kebersihan,piket,kepsek')->group(function () {
            Route::get('/', [KebersihanController::class, 'index'])->name('index');
            Route::post('/{lapor}/tindak', [KebersihanController::class, 'tindak'])->name('tindak');
            Route::get('/galeri', [KebersihanController::class, 'galeri'])->name('galeri');
        });
    });

    // Tata Tertib - guru/walikelas/piket bisa lapor, role tatib yang tindak lanjuti
    Route::prefix('tatib')->name('tatib.')->group(function () {
        Route::get('/cari', [TatibController::class, 'cari'])->name('cari');
        Route::get('/lapor/{siswa}', [TatibController::class, 'lapor'])->name('lapor');
        Route::post('/lapor/{siswa}', [TatibController::class, 'simpan'])->name('simpan');
        Route::get('/', [TatibController::class, 'index'])->name('index');
        Route::post('/{pelanggaran}/tindak', [TatibController::class, 'tindak'])->name('tindak');
    });

    // Bimbingan Konseling
    Route::prefix('bimbingan')->name('bimbingan.')->middleware('role:bk,tatib,piket,kepsek')->group(function () {
        Route::get('/cari', [BimbinganController::class, 'cari'])->name('cari');
        Route::get('/lapor/{siswa}', [BimbinganController::class, 'lapor'])->name('lapor');
        Route::post('/lapor/{siswa}', [BimbinganController::class, 'simpan'])->name('simpan');
        Route::get('/', [BimbinganController::class, 'index'])->name('index');
    });

    // Keagamaan - guru yang punya jadwal jam sholat lapor, role keagamaan lihat rekap
    Route::prefix('keagamaan')->name('keagamaan.')->group(function () {
        Route::get('/', [KeagamaanController::class, 'index'])->name('index')->middleware('role:guru');
        Route::post('/lapor/{siswa}', [KeagamaanController::class, 'simpan'])->name('simpan')->middleware('role:guru');
        Route::get('/rekap', [KeagamaanController::class, 'rekap'])->name('rekap')->middleware('role:keagamaan,piket,kepsek');
    });

    // Ajuan Absensi - Admin Absensi ajukan (keliling kelas), Piket yang ACC/Tolak.
    // Selama belum di-ACC, siswa dianggap belum tercatat absen (bisa jadi Alpha).
    Route::prefix('ajuan-absensi')->name('ajuan-absensi.')->group(function () {
        Route::middleware('role:admin')->group(function () {
            Route::get('/ajukan', [AjuanAbsensiController::class, 'pilihKelas'])->name('pilih-kelas');
            Route::get('/ajukan/{kelas}', [AjuanAbsensiController::class, 'ajukan'])->name('ajukan');
            Route::post('/ajukan-siswa/{siswa}', [AjuanAbsensiController::class, 'simpan'])->name('simpan');
            Route::get('/list', [AjuanAbsensiController::class, 'listAjuan'])->name('list');
            Route::delete('/{ajuan}/hapus', [AjuanAbsensiController::class, 'hapusAjuan'])->name('hapus-ajuan');
        });
        Route::middleware('role:piket')->group(function () {
            Route::get('/', [AjuanAbsensiController::class, 'index'])->name('index');
            Route::post('/{ajuan}/acc', [AjuanAbsensiController::class, 'acc'])->name('acc');
            Route::post('/{ajuan}/tolak', [AjuanAbsensiController::class, 'tolak'])->name('tolak');
        });
    });

    // Placeholder modul lain, supaya link di bottom-nav tidak 404 dulu.
    Route::get('/tugas', fn () => view('placeholder', ['judul' => 'Tugas Siswa']))->name('tugas.index');

    // Jadwal Pelajaran - lihat berdasarkan Kelas atau Guru, siapa saja yang login boleh lihat
    Route::prefix('jadwal')->name('jadwal.')->group(function () {
        Route::get('/', [JadwalController::class, 'index'])->name('index');
        Route::get('/kelas', [JadwalController::class, 'kelasGrid'])->name('kelas-grid');
        Route::get('/kelas/{kelas}', [JadwalController::class, 'kelasDetail'])->name('kelas');
        Route::get('/guru', [JadwalController::class, 'guruList'])->name('pilih-guru');
        Route::get('/guru/{guru}', [JadwalController::class, 'guruDetail'])->name('guru');
    });

    // Route generik untuk semua menu panel_*.php yang belum dimigrasi satu-satu.
    // Begitu modul aslinya jadi, ganti pemanggilannya di dashboard.blade.php
    // dari route('modul', 'slug-ini') ke route controller yang sesungguhnya.
    Route::get('/modul/{slug}', function (string $slug) {
        return view('placeholder', ['judul' => ucwords(str_replace('-', ' ', $slug))]);
    })->name('modul');
});
