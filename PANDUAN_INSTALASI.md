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
13. ✅ Warning Guru otomatis (Alpha, sering tidak masuk) + Ajukan Guru (kelas kosong, wajib konfirmasi)
14. ✅ Tugas Guru Absen — upload tugas dari halaman detail Jadwal Guru (khusus baris hari ini)
15. ✅ Absen Guru (piket) — list guru dengan link ke Jadwal, bukan CRUD
16. ✅ Arsip Surat — galeri foto bukti absensi (sakit/ijin) yang sudah terupload, filter tanggal

### Belum dimigrasi
- **Nilai PSAJ Tulis, dan sejenisnya** — ini BUKAN belum dikerjakan, tapi memang sistem/link/aplikasi TERPISAH di luar cakupan migrasi ini (sudah dikonfirmasi Bapak Ginanjar). Kalau nanti perlu ditautkan dari dashboard SIMT (misal jadi link keluar ke aplikasi itu), tinggal kasih tahu URL-nya, tinggal ganti 1 baris `href` di `dashboard.blade.php` untuk item "Nilai PSAJ Tulis".
- **E-rapor** — sama, terintegrasi sistem RADIG 2.0 eksternal, di luar cakupan migrasi ini.

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

## Fitur Superadmin (Panel AdminLTE Terpisah)

Panel superadmin pakai layout berbeda (AdminLTE 3.2, lewat CDN) supaya terlihat
lebih formal/administratif dibanding tampilan mobile-friendly biasa.

### Cara mengaktifkan akun Superadmin
Isi kolom `jabatan` di tabel `member` untuk akun yang dituju dengan teks **"Superadmin"**
(case-insensitive). Begitu login, akan muncul kartu ungu "Panel Superadmin" di
dashboard utama, klik untuk masuk.

### Migration baru
```bash
php artisan migrate
```
Ini menambah kolom `aktif` (boolean, default 1) di tabel `guru` - dipakai fitur
"Nonaktifkan" guru tanpa menghapus datanya sama sekali.

### Yang bisa dilakukan superadmin
- **Data Siswa**: Tambah, Edit, **Mutasi** (pindah kelas/tandai keluar-lulus).
  Sengaja **tidak ada Hapus** - supaya riwayat absensi/pelanggaran/bimbingan yang
  mengacu ke siswa itu tidak jadi rusak/yatim.
- **Data Guru & Roles**: Tambah, Edit, **Nonaktifkan/Aktifkan** (bukan hapus).
  Tombol "Roles" per guru untuk: buat akun login baru, atur jabatan login
  (isi "Superadmin" di sini untuk kasih akses superadmin ke guru lain), atur
  wali kelas, piket, dan semua flag role, serta reset password.
- **Data Absensi**: lihat & edit/hapus SEMUA data absensi (semua tanggal, bukan
  cuma hari ini seperti piket).
- **Data Pelanggaran**: edit/hapus semua laporan pelanggaran.
- **Data Bimbingan Konseling**: edit/hapus semua catatan BK.
- **Dashboard**: kartu statistik Absensi hari ini/bulan ini, Keterlambatan,
  Pelanggaran bulan ini, dan Notifikasi belum diaksi guru.

### Update Superadmin - tambahan
- **Data Kelas**: Tambah/Edit master data kelas (nama + kapasitas). Tanpa hapus,
  sama alasannya dengan Siswa/Guru.
- **Kelola Akun**: halaman terpisah dari "Roles" di Data Guru - untuk mengatur
  SEMUA akun login termasuk yang **tidak terhubung ke data guru manapun**
  (misal akun piket/admin murni). Bisa buat akun baru, atur roles, reset password.
- Panel Guru: menu "Rekap Kehadiran" dan "List Pelanggaran" dihapus (duplikat
  dengan menu Absensi Siswa panel lain dan tidak relevan untuk guru mapel biasa).

## Fitur Keamanan: Wajib Ganti Password & Log Aktivitas

Karena situs sekarang bisa diakses publik (ada halaman Jadwal tanpa login),
2 fitur keamanan tambahan:

### 1. Wajib Ganti Password
Migration baru menambah kolom `wajib_ganti_password` (default `true` untuk
SEMUA akun yang sudah ada) dan `last_login_at` di tabel `member`. Begitu
akun dengan flag ini login, langsung diarahkan ke halaman Ganti Password
(minimal 6 karakter) sebelum bisa akses menu lain manapun. Setelah ganti,
flag otomatis jadi `false`.

Akun baru yang dibuat lewat Superadmin (buat akun / buat akun guru / reset
password) otomatis di-set `wajib_ganti_password = true` juga.

```bash
php artisan migrate
```

**Penting:** kolom `id` di tabel `member` (sama seperti `datajadwal` sebelumnya)
ternyata BUKAN auto-increment - sudah diperbaiki juga di kode (Model
`Member` sekarang generate ID manual lewat `Member::idBerikutnya()`).

### 2. Log Aktivitas (Superadmin)
Menu baru "Log Aktivitas" di panel Superadmin, dikelompokkan per tab:
Absensi, Pelanggaran, Keterlambatan, Sistem, Lainnya - supaya tidak jadi
1 daftar campur aduk. Contoh isi: "Ginanjar Rahardi mencatat absen Fania
Zahra jadi Sakit".

Sudah tercatat otomatis untuk: isi/ubah/hapus absensi, catat terlambat,
lapor & tindak lanjut pelanggaran, login ke sistem, buat akun baru lewat
Superadmin. Kalau mau tambah pencatatan di modul lain, tinggal panggil
`\App\Models\LogAktivitas::catat('kategori', 'deskripsi kegiatan');` di
controller terkait.

## Fitur Bot WhatsApp - Ajuan Absensi Wali Murid

Pakai **Baileys** (library open-source, gratis, self-hosted - BUKAN API resmi
Meta). Ada 2 bagian yang perlu di-setup terpisah: bot Node.js dan sisi Laravel.

### PENTING - baca dulu sebelum setup
- Sebaiknya pakai **nomor WhatsApp terpisah** (bukan nomor pribadi utama), karena
  ini metode tidak resmi dan ada risiko kecil nomor bisa di-banned kalau
  pemakaiannya dianggap tidak wajar oleh WhatsApp.
- Proses bot harus **jalan terus-menerus** di server (pakai PM2), bukan sekadar
  dijalankan sekali lalu ditinggal.

### 1. Setup bot Node.js
```bash
cd whatsapp-bot
npm install
cp .env.example .env
```
Edit `.env`:
```
LARAVEL_WEBHOOK_URL=https://simt.sekolah.co.id/api/whatsapp/masuk
SHARED_SECRET=isi-dengan-token-acak-yang-panjang-dan-rahasia
PORT=3300
```

Jalankan sekali dulu untuk scan QR:
```bash
node index.js
```
Scan QR yang muncul di terminal pakai WhatsApp di HP (nomor yang sudah disiapkan
khusus untuk bot). Setelah tersambung ("✅ Bot WhatsApp terhubung"), matikan
(Ctrl+C), lalu jalankan permanen pakai PM2:
```bash
npm install -g pm2
pm2 start index.js --name simt-wa-bot
pm2 save
pm2 startup
```

### 2. Setup sisi Laravel
Tambahkan ke `.env` Laravel (SHARED_SECRET **harus sama persis** dengan punya bot):
```
WA_BOT_URL=http://127.0.0.1:3300
WA_BOT_SECRET=isi-dengan-token-acak-yang-panjang-dan-rahasia
```

```bash
php artisan migrate
```

**Kecualikan webhook dari proteksi CSRF** - tambahkan di `bootstrap/app.php`,
di dalam `->withMiddleware(function (Middleware $middleware) { ... })`:
```php
$middleware->validateCsrfTokens(except: [
    'api/whatsapp/masuk',
]);
```
(Wajib, karena bot Node.js kirim POST tanpa token CSRF - tanpa ini akan kena
error 419.)

### 3. Cara kerja alurnya
1. Wali murid kirim pesan apa saja ke nomor bot → bot balas menu, minta ketik "Absen"
2. Bot cocokkan nomor WA pengirim ke kolom `whatsapp` di tabel `datasiswa` -
   kalau nomor itu belum terhubung ke siswa manapun, wali diberi tahu untuk
   hubungi sekolah dulu (tambahkan/perbaiki nomor di Data Siswa)
3. Kalau 1 nomor = 2+ anak, bot tanya pilih yang mana dulu
4. Pilih Sakit/Ijin → bot minta foto surat → tersimpan ke `ajuan_whatsapp`
5. Piket buka menu **"Ajuan WhatsApp"**, ACC atau Tolak
6. Begitu di-ACC/ditolak, bot otomatis kirim pesan balasan ke wali murid

**Catatan:** supaya bot bisa mengenali wali murid, kolom `whatsapp` di tabel
siswa harus terisi nomor yang BENAR-BENAR dipakai wali untuk chat (format
angka saja, boleh pakai/tanpa awalan 62 - pencocokan pakai 10 digit terakhir
supaya fleksibel).
