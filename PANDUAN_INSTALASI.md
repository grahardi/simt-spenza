# Panduan pasang skeleton Laravel 13 ini di server beta

## UPDATE PENTING (setelah struktur asli `simtnew` diterima)
Semua tabel data (`tbl_member`, `absen_siswa`, `guru`, `kelas`, `member`, `wali_kelas`,
dst) **sudah ada** di database `simtnew` hasil clone, dengan struktur asli sistem lama.
Skeleton ini **tidak lagi membuat migration `create table` untuk tabel-tabel itu** —
model Eloklen langsung diarahkan ke tabel & primary key aslinya. Jangan buat migration
baru untuk tabel-tabel ini kecuali memang mau ubah struktur dengan sengaja.

Temuan penting dari `absen26.sql`:
- Data siswa aktif ada di **`tbl_member`** (bukan tabel `siswa`, yang datanya legacy/tidak lengkap).
- Kelas disimpan sebagai teks bebas di `tbl_member.jenis_member` (contoh "9 - A"),
  **tidak** foreign key ke tabel `kelas`.
- Login & role sistem lama ada di tabel **`member`** dengan kolom flag per-role
  (admin, walikelas, tatib, bk, piket, guru, keagamaan, kebersihan, kepsek) —
  ini beda dari tabel `users` standar Laravel/Breeze. Modul auth akan digarap
  terpisah untuk menjembatani ini (kemungkinan pakai custom guard, bukan Breeze
  polos), belum termasuk di skeleton ini.

## 1. Buat project Laravel baru (di server beta, bukan di sini)
```bash
composer create-project laravel/laravel:^13.0 simt-beta
cd simt-beta
```
Pastikan PHP 8.3 dan extension `mysqli`/`pdo_mysql` aktif.

## 2. Pasang paket tambahan
```bash
composer require laravel/breeze --dev
php artisan breeze:install blade
```
(Modul login penuh berbasis tabel `member` menyusul terpisah — Breeze dipasang
dulu untuk kerangka dasar, belum dipakai untuk autentikasi produksi.)

## 3. Salin file dari skeleton ini
- `app/Models/*.php` → `app/Models/`
- `app/Http/Controllers/*.php` → `app/Http/Controllers/`
- `resources/views/*` → `resources/views/`
- `routes/web.php` → gabung dengan punya Breeze (pertahankan bagian auth Breeze)

Tidak ada migration untuk disalin di update ini — tabel data sudah ada.

## 4. Setel `.env` ke database `simtnew`
```
DB_DATABASE=simtnew
DB_USERNAME=...
DB_PASSWORD=...
```

## 5. Migrate
```bash
php artisan migrate
```
Ini akan menjalankan:
- Migration bawaan Laravel/Breeze (`users`, `password_reset_tokens`, `sessions`, dst) — aman, tidak bentrok dengan tabel sekolah.
- **`rename_tbl_member_to_datasiswa`** — merename `tbl_member` → `datasiswa`,
  kolom `tanggal_gabung` → `nisn`, `jenis_member` → `kelas`. Ini mengubah
  tabel yang sudah ada (bukan bikin baru), jadi **backup dulu** sebelum migrate:
  ```bash
  mysqldump -u [user] -p simtnew tbl_member > backup_tbl_member_sebelum_rename.sql
  ```
  Kalau perlu batalkan: `php artisan migrate:rollback` (sudah ada method `down()`
  yang mengembalikan nama tabel/kolom seperti semula).

Tabel `siswa` (legacy, tidak dipakai aplikasi) dibiarkan apa adanya — silakan
di-backup lalu `DROP TABLE siswa;` manual kalau sudah yakin aman.

## 6. Jalankan
```bash
php artisan serve
```
Buka `/absensi` untuk lihat modul yang sudah jadi.

---

## Roadmap migrasi modul selanjutnya (urutan usulan)
1. ✅ Absensi Siswa — sudah jadi, sudah disesuaikan ke `tbl_member`/`absen_siswa` asli
2. **Autentikasi** — jembatani tabel `member` (role flag) ke sistem login Laravel
3. Data Master Siswa & Guru (CRUD) — dari `siswatambah.php`, `gurutambah.php`, `daftarnama.php`
4. Keterlambatan — dari `telatkelas.php`, `caritelat.php`, dst
5. Kebersihan Kelas — dari `bersihkelas.php` dkk
6. Tata Tertib (Tatib) — dari `tatibentry.php`, `tindakan.php` dkk
7. Bimbingan Konseling — dari `bimbingan.php` dkk
8. Keagamaan — dari `agamalistall.php` dkk
9. RPP, Peminjaman Alat (Smart), Surat/Arsip, Tugas, Jadwal

Kirim saja pesan "lanjut modul [nama]" kalau mau saya kerjakan modul berikutnya.
