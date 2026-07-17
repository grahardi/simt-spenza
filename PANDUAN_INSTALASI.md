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
- `app/Auth/*.php` → `app/Auth/`
- `app/Providers/LegacyAuthServiceProvider.php` → `app/Providers/`
- `app/Http/Controllers/**/*.php` → `app/Http/Controllers/`
- `app/Http/Middleware/*.php` → `app/Http/Middleware/`
- `resources/views/*` → `resources/views/`
- `routes/web.php` → **timpa total** punya Breeze (modul ini pakai login sendiri berbasis tabel `member`, bukan Breeze)

### 3a. Daftarkan service provider baru
Di `bootstrap/providers.php`, tambahkan:
```php
return [
    App\Providers\AppServiceProvider::class,
    App\Providers\LegacyAuthServiceProvider::class, // baris baru
];
```

### 3b. Daftarkan middleware alias `role`
Di `bootstrap/app.php`, di dalam `->withMiddleware(function (Middleware $middleware) { ... })`:
```php
$middleware->alias([
    'role' => \App\Http\Middleware\EnsureHasRole::class,
]);
```

### 3c. Tambahkan guard & provider `member` di `config/auth.php`
```php
'guards' => [
    'web' => [...], // bawaan Breeze, boleh dibiarkan untuk kebutuhan lain
    'member' => [
        'driver' => 'session',
        'provider' => 'members',
    ],
],

'providers' => [
    'users' => [...], // bawaan Breeze
    'members' => [
        'driver' => 'eloquent-legacy', // didaftarkan oleh LegacyAuthServiceProvider
        'model' => App\Models\Member::class,
    ],
],
```

Login memakai nomor ID (kolom `user`), bukan email — sesuai kebiasaan akun yang
sudah ada, jadi tidak perlu migrasi data akun sama sekali.

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
2. ✅ Autentikasi — login pakai nomor ID + password, role dari tabel `member`
3. ✅ Data Master Siswa & Guru (CRUD) — sudah jadi, dashboard berbasis panel per-role
4. ✅ Absensi Piket — isi absensi manual (Sakit/Ijin/Alfa/Dispensasi), catat & lihat siswa terlambat
5. ✅ Ajuan Absensi — Admin Absensi keliling kelas ajukan (belum resmi), Piket ACC/Tolak (baru resmi masuk absen_siswa)
6. ✅ Kebersihan Kelas — lapor kelas kotor (guru manapun, foto), tindak lanjut + galeri sebelum/sesudah (role kebersihan) - termasuk melengkapi fitur "upload aksi" yang di kode lama filenya kosong/belum jadi
7. ✅ Tata Tertib (Tatib) — lapor pelanggaran siswa (kategori/poin), list + tindak lanjut
8. ✅ Bimbingan Konseling — entry catatan bimbingan (kategori/keterangan/tindakan/foto), list
9. ✅ Keagamaan — guru berjadwal jam sholat (jamhari 'x') lapor Halangan/Bolos/Ijin, rekap semua
10. ✅ RPP — guru upload PDF per bulan, kepala sekolah setujui
11. ✅ Peminjaman Ruang Serbaguna (dulu salah label "Smartboard") — kalender 5 hari x jam, booking
12. ✅ Notifikasi — guru lihat warning/ajuan yang ditujukan ke dirinya dari tabel `warning`

### Belum dimigrasi (butuh info lebih dalam / scope terpisah)
- **Surat/Arsip** — arsip surat masuk/keluar sekolah
- **Tugas Guru Absen (piket)** — guru absen upload tugas, piket sampaikan ke kelas
- **DKN Kelas** (walikelas) — daftar kumpulan nilai per kelas
- **Nilai PSAJ Tulis** (guru) — input nilai ujian
- **Kehadiran Guru/Ketidakhadiran** (kepsek) — terkait tabel `warning`/GTK, laporan piket soal guru tidak hadir
- **E-rapor** — terintegrasi sistem RADIG 2.0 eksternal, di luar cakupan migrasi ini

### Modul kecil yang baru ditambahkan
- ✅ **Jadwal Mengajar** (guru) — jadwal hari ini dari tabel `datajadwal`, filter otomatis pakai hari & guru yang login
- ✅ **Aktivitas Kelas** (wali kelas) — rekap absensi hari ini untuk kelas yang diampu (dari kolom `member.walikelas`, isinya nama kelas langsung seperti "7 - A", bukan flag 0/1)

## Warning Otomatis untuk Wali Kelas (Siswa Alpha & Sering Tidak Masuk)

Command `warning:cek-otomatis` perlu dijadwalkan jalan otomatis tiap hari.
Tambahkan 1 baris cron ini di server (`crontab -e`):
```
* * * * * cd /path/ke/project && php artisan schedule:run >> /dev/null 2>&1
```
Laravel sendiri yang akan menjalankan `warning:cek-otomatis` jam 15:00 tiap
hari (jadwalnya ada di `routes/console.php`). Bisa dites manual dulu:
```bash
php artisan warning:cek-otomatis
```


Kode guru di file Excel (02-51, dari sheet "kodeguru") **beda** dengan `id_guru` asli
di database - jadi harus dicocokkan dulu lewat nama (fuzzy match), baru bisa dipakai
mengisi tabel `datajadwal`. Prosesnya sengaja 2 langkah (pratinjau dulu, baru apply)
supaya bisa dicek manual dulu sebelum data beneran masuk.

### 1. Salin file yang diperlukan
- `database/data/kodeguru_reference.php` (hasil ekstrak sheet "kodeguru", 49 baris)
- `database/data/jadwal_matrix.php` (hasil ekstrak sheet "jadwal", 1260 baris jadwal Senin-Jumat)
- `database/migrations/2026_01_04_000001_create_kodeguru_table.php`
- `app/Models/KodeGuru.php`
- `app/Console/Commands/SinkronJadwalGuru.php`

### 2. Migrate
```bash
php artisan migrate
```

### 3. Jalankan pratinjau (TIDAK mengubah data apapun)
```bash
php artisan jadwal:sinkron
```
Ini akan menampilkan tabel: kode Excel, nama di Excel, nama yang paling mirip
ditemukan di tabel `guru`, skor kemiripan (%), dan status. Skor >= 60% dianggap
cocok otomatis, di bawah itu ditandai "perlu cek manual".

Hasil pencocokan (termasuk yang di bawah 60%) disimpan ke tabel `kodeguru` untuk
direview. Kalau ada yang salah cocok atau butuh dikoreksi manual, edit langsung
kolom `id_guru` di tabel `kodeguru` (lewat phpMyAdmin atau `php artisan tinker`)
sebelum lanjut ke langkah berikutnya.

**Kode yang tidak ada gurunya sama sekali di database** (skor rendah/tidak ada
kandidat sama sekali) otomatis dilewati saat isi jadwal - tidak perlu dihapus manual
satu-satu.

### 4. Setelah dicek, isi tabel datajadwal
```bash
php artisan jadwal:sinkron --apply
```
Ini mengisi/update `datajadwal` berdasarkan hasil pencocokan di tabel `kodeguru`.
Baris dengan kode yang tidak berhasil dicocokkan (skor < 60% atau memang tidak ada
di tabel `kodeguru`) otomatis dilewati - laporan di akhir menampilkan berapa baris
masuk dan berapa dilewati.


## Catatan tambahan: pagination pakai Bootstrap, bukan default
Ikon panah pagination Laravel default didesain untuk Tailwind (SVG tanpa
constraint ukuran) - kalau project pakai Bootstrap, ikonnya tampil raksasa/
tidak ke-style. Tambahkan baris ini di `app/Providers/AppServiceProvider.php`,
di dalam method `boot()`:
```php
use Illuminate\Pagination\Paginator;

public function boot(): void
{
    Paginator::useBootstrapFive();
}
```
Ini cukup ditambahkan sekali, berlaku untuk semua `->links()` di seluruh
aplikasi (Absensi, Siswa, Guru, dst).

Password akun-akun lama di tabel `member` masih **plain text** (belum di-hash).
`LegacyPasswordEloquentUserProvider` menangani ini secara otomatis: begitu
seorang user login sukses, password langsung di-hash ulang (bcrypt) dan
disimpan — tidak perlu reset password massal, dan tidak ada jendela waktu
di mana user tidak bisa login. Setelah semua akun aktif pernah login sekali
lewat sistem baru, seluruh isi kolom password akan otomatis ter-hash semua.

Kirim saja pesan "lanjut modul [nama]" kalau mau saya kerjakan modul berikutnya.
