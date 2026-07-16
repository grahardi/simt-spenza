# Panduan pasang skeleton Laravel 13 ini di server beta

## 1. Buat project Laravel baru (di server beta, bukan di sini)
```bash
composer create-project laravel/laravel:^13.0 simt-beta
cd simt-beta
```
Pastikan PHP 8.3 dan extension `mysqli`/`pdo_mysql` aktif.

## 2. Pasang paket tambahan yang dipakai skeleton ini
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
composer require spatie/laravel-permission
php artisan vendor:publish --provider="Spatie\Permission\PermissionServiceProvider"
```
`spatie/laravel-permission` dipakai untuk role (guru, walikelas, kepsek, BK, tatib,
kebersihan, piket, keagamaan, admin) menggantikan file-file `panel_*.php` terpisah
di sistem lama.

## 3. Salin file-file dari skeleton ini ke project barunya
- `database/migrations/*.php` → `database/migrations/`
- `app/Models/*.php` → `app/Models/`
- `app/Http/Controllers/*.php` → `app/Http/Controllers/`
- `resources/views/*` → `resources/views/`
- `routes/web.php` → timpa punya Breeze (gabung bagian auth-nya)

## 4. Setel `.env` ke database clone Bapak
```
DB_DATABASE=nama_db_clone
DB_USERNAME=...
DB_PASSWORD=...
```

## 5. Jalankan migrasi
```bash
php artisan migrate
```
⚠️ Catatan penting: migrations di skeleton ini saya susun berdasarkan **nama kolom
yang saya temukan dari query di kode lama** (`absenjelas.php` dkk), bukan dari dump
struktur database asli. Kemungkinan besar sudah cukup akurat untuk tabel
`absen_siswa`, tapi untuk `siswa`, `guru`, `kelas` sebaiknya Bapak bandingkan dulu
dengan struktur asli di database clone sebelum `migrate`. Kalau nanti Bapak kirim
`mysqldump --no-data`, saya sesuaikan migrations-nya jadi 100% akurat.

## 6. Jalankan
```bash
php artisan serve
```
Buka `/absensi` untuk lihat modul yang sudah jadi.

---

## Roadmap migrasi modul selanjutnya (urutan usulan)
1. ✅ Absensi Siswa — sudah jadi
2. Data Master Siswa & Guru (CRUD) — dari `siswatambah.php`, `gurutambah.php`, `daftarnama.php`
3. Keterlambatan — dari `telatkelas.php`, `caritelat.php`, dst
4. Kebersihan Kelas — dari `bersihkelas.php` dkk
5. Tata Tertib (Tatib) — dari `tatibentry.php`, `tindakan.php` dkk
6. Bimbingan Konseling — dari `bimbingan.php` dkk
7. Keagamaan — dari `agamalistall.php` dkk
8. RPP, Peminjaman Alat (Smart), Surat/Arsip, Tugas, Jadwal

Setiap modul saya kerjakan dengan pola yang sama seperti Absensi Siswa:
migration → model → controller (Eloquent, bukan query mentah) → view Blade
(auto-escape, aman XSS) → route dengan middleware role yang jelas.

Kirim saja pesan "lanjut modul [nama]" kalau mau saya kerjakan modul berikutnya.
