<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SIMT') - Sistem Informasi Manajemen Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { padding-bottom: 70px; background: #f4f5f7; }
        .bottom-nav {
            position: fixed; bottom: 0; left: 0; right: 0;
            background: #fff; border-top: 1px solid #e5e5e5;
            display: flex; justify-content: space-around; padding: 6px 0;
            z-index: 1030;
        }
        .bottom-nav a { color: #6c757d; text-align: center; font-size: 11px; flex: 1; }
        .bottom-nav a.active { color: #4b0082; }
        .bottom-nav i { display: block; font-size: 20px; margin-bottom: 2px; }
        @media (min-width: 768px) {
            body { padding-bottom: 0; }
            .bottom-nav { display: none; }
        }

        /* Menu atas: tombol ikon + teks (Home, Profil, Notifikasi, Logout) */
        .top-nav { display: flex; align-items: center; gap: 4px; margin-left: auto; }
        .top-nav a, .top-nav button {
            display: flex; align-items: center; gap: 6px;
            color: #fff; text-decoration: none; background: none; border: none;
            padding: 6px 10px; border-radius: 8px; font-size: 13px;
        }
        .top-nav a:hover, .top-nav button:hover, .top-nav a.active { background: rgba(255,255,255,.15); color: #fff; }
        .top-nav span.label { display: none; }
        @media (min-width: 576px) {
            .top-nav span.label { display: inline; }
        }
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .menu-card {
            background: #fff;
            border-radius: 16px;
            padding: 18px 6px 14px;
            text-align: center;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            color: inherit;
            border: 1px solid #f0f0f0;
            box-shadow: 0 4px 6px rgba(0,0,0,0.06), inset 0 -3px 0 rgba(0,0,0,0.05);
            transition: transform .15s ease, box-shadow .15s ease;
        }
        .menu-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 14px rgba(0,0,0,0.12);
            text-decoration: none;
        }
        .menu-icon {
            width: 52px; height: 52px; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 8px; font-size: 22px;
        }
        .menu-title {
            font-weight: 600;
            font-size: 13px;
            line-height: 1.2;
            color: #3a3a3a;
        }
        /* Palet warna kartu - dipetakan per kategori menu, bukan acak */
        .bg-blue   { background: #e6f1fb; color: #185fa5; }
        .bg-teal   { background: #e1f5ee; color: #0f6e56; }
        .bg-coral  { background: #faece7; color: #993c1d; }
        .bg-pink   { background: #fbeaf0; color: #993556; }
        .bg-amber  { background: #faeeda; color: #854f0b; }
        .bg-green  { background: #eaf3de; color: #3b6d11; }
        .bg-purple { background: #eeedfe; color: #534ab7; }
        .bg-red    { background: #fcebeb; color: #a32d2d; }
        @media (max-width: 576px) {
            .menu-grid { gap: 8px; }
            .menu-card { padding: 12px 2px 10px; border-radius: 12px; }
            .menu-icon { width: 40px; height: 40px; font-size: 17px; margin-bottom: 6px; }
            .menu-title { font-size: 11px; }
        }

        /* Tombol aksi absensi (Sakit/Ijin/Alfa/Dispensasi/Terlambat) */
        .siswa-row {
            display: flex; flex-direction: column; gap: 10px;
            padding: 16px 0;
        }
        @media (min-width: 768px) {
            .siswa-row { flex-direction: row; align-items: center; }
            .siswa-info { flex: 1; }
        }
        .siswa-aksi { display: flex; flex-wrap: wrap; gap: 8px; }

        /* Baris siswa ringkas untuk Ajukan Absensi - 1 baris (No.Induk-Nama),
           info "kemarin" kecil di bawahnya, tombol di kanan */
        .siswa-row-ringkas {
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px; padding: 10px 0; flex-wrap: wrap;
        }
        .siswa-nama { font-weight: 600; font-size: 14px; }
        .btn-absen {
            display: inline-flex; align-items: center;
            border: none; border-radius: 999px;
            padding: 8px 16px; font-size: 13px; font-weight: 600;
            cursor: pointer; transition: transform .1s ease, box-shadow .1s ease;
        }
        .btn-absen:hover { transform: translateY(-1px); box-shadow: 0 3px 8px rgba(0,0,0,.15); }
        .btn-absen-sakit { background: #faeeda; color: #854f0b; }
        .btn-absen-ijin { background: #eaf3de; color: #3b6d11; }
        .btn-absen-alfa { background: #fcebeb; color: #a32d2d; }
        .btn-absen-dispensasi { background: #e6f1fb; color: #185fa5; }
        .btn-absen-telat { background: #eeedfe; color: #534ab7; }
        .btn-absen-aktif {
            box-shadow: inset 0 0 0 2px currentColor;
            opacity: 1;
        }
        .badge-status {
            display: inline-flex; align-items: center;
            border-radius: 999px; padding: 4px 12px;
            font-size: 12px; font-weight: 600;
        }
        .badge-s { background: #faeeda; color: #854f0b; }
        .badge-i { background: #eaf3de; color: #3b6d11; }
        .badge-a { background: #fcebeb; color: #a32d2d; }
        .badge-d { background: #e6f1fb; color: #185fa5; }

        /* Grid kartu kelas untuk Ajukan Absensi - pengganti laporlistkelas.php.
           Kotak dibuat ukuran tetap kecil (~70px, mirip tombol lama), BUKAN
           mengisi penuh lebar kolom - supaya tidak raksasa di layar lebar. */
        .kelas-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 10px 6px;
        }
        .kelas-btn {
            display: flex; align-items: center; justify-content: center;
            width: 70px; height: 70px; justify-self: center;
            border-radius: 14px;
            font-weight: 700; font-size: 20px; text-decoration: none;
            color: #fff; transition: transform .1s ease;
        }
        .kelas-btn:hover { transform: translateY(-2px); color: #fff; }
        .kelas-7 { background: #1abc9c; }
        .kelas-8 { background: #c9971f; }
        .kelas-9 { background: #c0392b; }
        .kelas-lain { background: #6c757d; }
        @media (max-width: 576px) {
            .kelas-grid { gap: 8px 4px; }
            .kelas-btn { width: 56px; height: 56px; font-size: 16px; border-radius: 10px; }
        }

        /* Panel dashboard - tema warna light beda per role, biar gampang dibedakan */
        .panel-section {
            border-radius: 18px; padding: 16px 16px 18px; margin-bottom: 18px;
            border: 1px solid rgba(0,0,0,.04);
        }
        .panel-title {
            font-weight: 700; font-size: 14px; text-align: center;
            border-radius: 10px; padding: 8px 12px; margin-bottom: 14px;
        }
        .panel-theme-blue   { background: #eef6fd; } .panel-theme-blue .panel-title   { background: #d3e9fb; color: #185fa5; }
        .panel-theme-teal   { background: #eafaf5; } .panel-theme-teal .panel-title   { background: #cdf1e5; color: #0f6e56; }
        .panel-theme-amber  { background: #fef8ec; } .panel-theme-amber .panel-title  { background: #faedc9; color: #854f0b; }
        .panel-theme-coral  { background: #fdf2ee; } .panel-theme-coral .panel-title  { background: #f8ddd1; color: #993c1d; }
        .panel-theme-pink   { background: #fdeff4; } .panel-theme-pink .panel-title   { background: #f9d9e5; color: #993556; }
        .panel-theme-green  { background: #f2f8ea; } .panel-theme-green .panel-title  { background: #ddedc8; color: #3b6d11; }
        .panel-theme-purple { background: #f3f1fe; } .panel-theme-purple .panel-title { background: #e0dbfc; color: #534ab7; }
        .panel-theme-red    { background: #fdedec; } .panel-theme-red .panel-title    { background: #f9d4d2; color: #a32d2d; }
        .panel-section .menu-card { background: rgba(255,255,255,.85); }

        /* Avatar inisial di tabel Absensi Siswa */
        .avatar-inisial {
            width: 32px; height: 32px; border-radius: 50%;
            background: #eeedfe; color: #534ab7;
            display: inline-flex; align-items: center; justify-content: center;
            font-weight: 700; font-size: 13px; flex-shrink: 0;
        }
        .absensi-table thead th {
            font-size: 12px; text-transform: uppercase; letter-spacing: .04em;
            color: #888; font-weight: 700; border-bottom-width: 2px;
        }
    </style>
</head>
<body>
    <header class="bg-indigo text-white p-2 shadow" style="background:#4b0082;">
        <div class="container d-flex align-items-center">
            <span class="fw-semibold">SIMT Sekolah</span>

            <nav class="top-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i><span class="label">Home</span>
                </a>
                <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">
                    <i class="fas fa-user-circle"></i><span class="label">Profil</span>
                </a>
                <a href="{{ route('modul', 'notifikasi') }}" class="{{ request()->routeIs('modul') && request()->route('slug') === 'notifikasi' ? 'active' : '' }}">
                    <i class="fas fa-bell"></i><span class="label">Notifikasi</span>
                </a>
                <form method="POST" action="{{ route('logout') }}" class="m-0">
                    @csrf
                    <button type="submit"><i class="fas fa-sign-out-alt"></i><span class="label">Logout</span></button>
                </form>
            </nav>
        </div>
    </header>

    <main class="container py-3">
        @yield('content')
    </main>

    {{-- Menu bawah berbasis ikon untuk mobile, menggantikan pola menu.php lama --}}
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>Depan
        </a>
        <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i>Absensi
        </a>
        <a href="{{ route('siswa.index') }}" class="{{ request()->routeIs('siswa.*') ? 'active' : '' }}">
            <i class="fas fa-user-graduate"></i>Siswa
        </a>
        <a href="{{ route('guru.index') }}" class="{{ request()->routeIs('guru.*') ? 'active' : '' }}">
            <i class="fas fa-chalkboard-teacher"></i>Guru
        </a>
        <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">
            <i class="fas fa-user"></i>Profil
        </a>
    </nav>
</body>
</html>
