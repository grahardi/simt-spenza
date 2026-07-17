<?php

use App\Http\Controllers\Auth\MemberLoginController;
use App\Http\Controllers\AbsensiBulananController;
use App\Http\Controllers\AbsensiSiswaController;
use App\Http\Controllers\AjuanAbsensiController;
use App\Http\Controllers\AjuanGuruController;
use App\Http\Controllers\AktivitasKelasController;
use App\Http\Controllers\BimbinganController;
use App\Http\Controllers\GantiPasswordController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\JadwalController;
use App\Http\Controllers\JadwalGuruController;
use App\Http\Controllers\NotifikasiController;
use App\Http\Controllers\KeagamaanController;
use App\Http\Controllers\RppController;
use App\Http\Controllers\SmartController;
use App\Http\Controllers\Superadmin\AbsensiController as SuperadminAbsensiController;
use App\Http\Controllers\Superadmin\AkunController as SuperadminAkunController;
use App\Http\Controllers\Superadmin\BkController as SuperadminBkController;
use App\Http\Controllers\Superadmin\DashboardController as SuperadminDashboardController;
use App\Http\Controllers\Superadmin\GuruController as SuperadminGuruController;
use App\Http\Controllers\Superadmin\KelasController as SuperadminKelasController;
use App\Http\Controllers\Superadmin\LogAktivitasController as SuperadminLogAktivitasController;
use App\Http\Controllers\Superadmin\LogLoginController as SuperadminLogLoginController;
use App\Http\Controllers\Superadmin\PelanggaranController as SuperadminPelanggaranController;
use App\Http\Controllers\Superadmin\SiswaController as SuperadminSiswaController;
use App\Http\Controllers\TugasController;
use App\Http\Controllers\ArsipSuratController;
use App\Http\Controllers\DknKelasController;
use App\Http\Controllers\FotoSiswaController;
use App\Http\Controllers\ProfilSiswaController;
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

// Jadwal Pelajaran versi PUBLIK - bisa diakses & dibagikan tanpa perlu login
Route::prefix('jadwal-publik')->name('jadwal-publik.')->group(function () {
    Route::get('/', [JadwalController::class, 'index'])->name('index');
    Route::get('/kelas', [JadwalController::class, 'kelasGrid'])->name('kelas-grid');
    Route::get('/kelas/{kelas}', [JadwalController::class, 'kelasDetail'])->name('kelas');
    Route::get('/guru', [JadwalController::class, 'guruList'])->name('pilih-guru');
    Route::get('/guru/{guru}', [JadwalController::class, 'guruDetail'])->name('guru');
});
Route::post('/logout', [MemberLoginController::class, 'destroy'])->name('logout');

Route::middleware(['auth:member', \App\Http\Middleware\ForcePasswordChange::class])->group(function () {

    Route::get('/ganti-password', [GantiPasswordController::class, 'form'])->name('ganti-password');
    Route::post('/ganti-password', [GantiPasswordController::class, 'simpan'])->name('ganti-password.simpan');

    Route::get('/', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profil', function () {
        return view('profil');
    })->name('profil');

    Route::get('/notifikasi', [NotifikasiController::class, 'index'])->name('notifikasi');
    Route::post('/notifikasi/{warning}/konfirmasi', [AjuanGuruController::class, 'konfirmasi'])->name('notifikasi.konfirmasi');

    // Ajukan Guru (kepsek lapor kelas kosong, guru wajib konfirmasi alasan lewat Notifikasi)
    Route::prefix('ajuan-guru')->name('ajuan-guru.')->middleware('role:kepsek')->group(function () {
        Route::get('/', [AjuanGuruController::class, 'form'])->name('form');
        Route::post('/{guru}', [AjuanGuruController::class, 'simpan'])->name('simpan');
        Route::get('/list', [AjuanGuruController::class, 'list'])->name('list');
    });

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

    Route::get('/siswa/{siswa}/profil', [ProfilSiswaController::class, 'show'])
        ->name('siswa.profil')
        ->middleware('role:guru,walikelas,kepsek,admin,piket');

    // Data Master Guru - pengganti gurutambah.php, arsipguru.php
    Route::resource('guru', GuruController::class)
        ->middleware('role:kepsek,admin,piket');

    // Absen Guru (piket) - list guru, link ke jadwal (bukan CRUD)
    Route::get('/absen-guru', [GuruController::class, 'absenList'])
        ->name('guru.absen-list')
        ->middleware('role:piket,kepsek');

    // Upload Tugas untuk kelas (guru absen) - dari halaman detail jadwal guru
    Route::prefix('tugas')->name('tugas.')->group(function () {
        Route::get('/upload/{guru}/{kelas}', [TugasController::class, 'upload'])->name('upload');
        Route::post('/upload/{guru}/{kelas}', [TugasController::class, 'simpan'])->name('simpan');
    });

    // Arsip Surat - berkas/foto bukti absensi yang sudah terupload
    Route::get('/arsip-surat', [ArsipSuratController::class, 'index'])
        ->name('arsip-surat')
        ->middleware('role:piket,kepsek,admin');

    Route::get('/absensi-bulanan', [AbsensiBulananController::class, 'index'])
        ->name('absensi-bulanan')
        ->middleware('role:tatib,kepsek,piket');

    // ===== AREA SUPERADMIN (layout AdminLTE terpisah) =====
    Route::prefix('superadmin')->name('superadmin.')->middleware('role:superadmin')->group(function () {
        Route::get('/', [SuperadminDashboardController::class, 'index'])->name('dashboard');

        Route::get('/siswa', [SuperadminSiswaController::class, 'index'])->name('siswa.index');
        Route::get('/siswa/tambah', [SuperadminSiswaController::class, 'create'])->name('siswa.create');
        Route::post('/siswa', [SuperadminSiswaController::class, 'store'])->name('siswa.store');
        Route::get('/siswa/{siswa}/edit', [SuperadminSiswaController::class, 'edit'])->name('siswa.edit');
        Route::put('/siswa/{siswa}', [SuperadminSiswaController::class, 'update'])->name('siswa.update');
        Route::get('/siswa/{siswa}/mutasi', [SuperadminSiswaController::class, 'mutasiForm'])->name('siswa.mutasi-form');
        Route::post('/siswa/{siswa}/mutasi', [SuperadminSiswaController::class, 'mutasi'])->name('siswa.mutasi');

        Route::get('/kelas', [SuperadminKelasController::class, 'index'])->name('kelas.index');
        Route::get('/kelas/tambah', [SuperadminKelasController::class, 'create'])->name('kelas.create');
        Route::post('/kelas', [SuperadminKelasController::class, 'store'])->name('kelas.store');
        Route::get('/kelas/{kelas}/edit', [SuperadminKelasController::class, 'edit'])->name('kelas.edit');
        Route::put('/kelas/{kelas}', [SuperadminKelasController::class, 'update'])->name('kelas.update');

        Route::get('/akun', [SuperadminAkunController::class, 'index'])->name('akun.index');
        Route::get('/akun/tambah', [SuperadminAkunController::class, 'create'])->name('akun.create');
        Route::post('/akun', [SuperadminAkunController::class, 'store'])->name('akun.store');
        Route::get('/akun/{akun}/edit', [SuperadminAkunController::class, 'edit'])->name('akun.edit');
        Route::put('/akun/{akun}', [SuperadminAkunController::class, 'update'])->name('akun.update');
        Route::post('/akun/{akun}/reset-password', [SuperadminAkunController::class, 'resetPassword'])->name('akun.reset-password');

        Route::get('/guru', [SuperadminGuruController::class, 'index'])->name('guru.index');
        Route::get('/guru/tambah', [SuperadminGuruController::class, 'create'])->name('guru.create');
        Route::post('/guru', [SuperadminGuruController::class, 'store'])->name('guru.store');
        Route::get('/guru/{guru}/edit', [SuperadminGuruController::class, 'edit'])->name('guru.edit');
        Route::put('/guru/{guru}', [SuperadminGuruController::class, 'update'])->name('guru.update');
        Route::post('/guru/{guru}/toggle-aktif', [SuperadminGuruController::class, 'toggleAktif'])->name('guru.toggle-aktif');
        Route::get('/guru/{guru}/roles', [SuperadminGuruController::class, 'roles'])->name('guru.roles');
        Route::post('/guru/{guru}/roles', [SuperadminGuruController::class, 'simpanRoles'])->name('guru.roles.simpan');
        Route::post('/guru/{guru}/buat-akun', [SuperadminGuruController::class, 'buatAkun'])->name('guru.buat-akun');
        Route::post('/guru/{guru}/reset-password', [SuperadminGuruController::class, 'resetPassword'])->name('guru.reset-password');

        Route::get('/absensi', [SuperadminAbsensiController::class, 'index'])->name('absensi.index');
        Route::get('/absensi/{absen}/edit', [SuperadminAbsensiController::class, 'edit'])->name('absensi.edit');
        Route::put('/absensi/{absen}', [SuperadminAbsensiController::class, 'update'])->name('absensi.update');
        Route::delete('/absensi/{absen}', [SuperadminAbsensiController::class, 'destroy'])->name('absensi.destroy');

        Route::get('/pelanggaran', [SuperadminPelanggaranController::class, 'index'])->name('pelanggaran.index');
        Route::get('/pelanggaran/{pelanggaran}/edit', [SuperadminPelanggaranController::class, 'edit'])->name('pelanggaran.edit');
        Route::put('/pelanggaran/{pelanggaran}', [SuperadminPelanggaranController::class, 'update'])->name('pelanggaran.update');
        Route::delete('/pelanggaran/{pelanggaran}', [SuperadminPelanggaranController::class, 'destroy'])->name('pelanggaran.destroy');

        Route::get('/bk', [SuperadminBkController::class, 'index'])->name('bk.index');
        Route::get('/bk/{bkItem}/edit', [SuperadminBkController::class, 'edit'])->name('bk.edit');
        Route::put('/bk/{bkItem}', [SuperadminBkController::class, 'update'])->name('bk.update');
        Route::delete('/bk/{bkItem}', [SuperadminBkController::class, 'destroy'])->name('bk.destroy');

        Route::get('/log', [SuperadminLogAktivitasController::class, 'index'])->name('log.index');
        Route::get('/log-login', [SuperadminLogLoginController::class, 'index'])->name('log-login.index');
    });

    // DKN Kelas - wali kelas upload berkas per mapel
    Route::prefix('dkn')->name('dkn.')->middleware('role:walikelas')->group(function () {
        Route::get('/', [DknKelasController::class, 'index'])->name('index');
        Route::post('/', [DknKelasController::class, 'simpan'])->name('simpan');
    });

    // Foto Siswa - gallery by kelas + pencarian + upload/ganti foto
    Route::prefix('foto-siswa')->name('foto-siswa.')->middleware('role:guru,walikelas,kepsek,admin,piket')->group(function () {
        Route::get('/', [FotoSiswaController::class, 'pilihKelas'])->name('pilih-kelas');
        Route::get('/kelas/{kelas}', [FotoSiswaController::class, 'kelas'])->name('kelas');
        Route::post('/upload/{siswa}', [FotoSiswaController::class, 'upload'])->name('upload');
    });

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

    // RPP - guru upload, kepala sekolah setujui
    Route::prefix('rpp')->name('rpp.')->group(function () {
        Route::get('/upload', [RppController::class, 'upload'])->name('upload')->middleware('role:guru');
        Route::post('/upload', [RppController::class, 'simpan'])->name('simpan')->middleware('role:guru');
        Route::get('/semua', [RppController::class, 'semua'])->name('semua')->middleware('role:kepsek');
        Route::post('/{rppItem}/setujui', [RppController::class, 'setujui'])->name('setujui')->middleware('role:kepsek');
    });

    // Peminjaman Ruang Serbaguna (nama lama: "smart")
    Route::prefix('smart')->name('smart.')->middleware('role:guru,kepsek,piket')->group(function () {
        Route::get('/', [SmartController::class, 'kalender'])->name('kalender');
        Route::get('/pinjam/{tanggal}/{jam}', [SmartController::class, 'pinjam'])->name('pinjam');
        Route::post('/pinjam/{tanggal}/{jam}', [SmartController::class, 'simpan'])->name('simpan');
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
