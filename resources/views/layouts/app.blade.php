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
        .desktop-nav { display: none; }
        @media (min-width: 768px) {
            body { padding-bottom: 0; }
            .bottom-nav { display: none; }
            .desktop-nav { display: flex; gap: 4px; margin-left: 24px; }
            .desktop-nav a {
                color: rgba(255,255,255,.85); text-decoration: none;
                padding: 6px 12px; border-radius: 6px; font-size: 14px;
            }
            .desktop-nav a i { margin-right: 6px; }
            .desktop-nav a.active, .desktop-nav a:hover { background: rgba(255,255,255,.15); color: #fff; }
        }

        /* Grid kartu menu ikon - pengganti pola panel_*.php lama, mobile-friendly */
        .menu-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
        }
        .menu-card {
            background: #fff;
            border-radius: 14px;
            padding: 16px 6px;
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
        .menu-card i {
            font-size: 26px;
            color: #4b0082;
            margin-bottom: 8px;
        }
        .menu-title {
            color: #e67e23;
            font-weight: 600;
            font-size: 13px;
            line-height: 1.2;
        }
        @media (max-width: 576px) {
            .menu-grid { gap: 8px; }
            .menu-card { padding: 10px 2px; border-radius: 10px; }
            .menu-card i { font-size: 20px; margin-bottom: 6px; }
            .menu-title { font-size: 11px; }
        }
    </style>
</head>
<body>
    <header class="bg-indigo text-white p-2 shadow" style="background:#4b0082;">
        <div class="container d-flex align-items-center">
            <span class="fw-semibold">SIMT Sekolah</span>

            {{-- Menu untuk desktop, menggantikan pola panel_*.php per-role lama --}}
            <nav class="desktop-nav">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-home"></i>Depan
                </a>
                <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-check"></i>Absensi
                </a>
                <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
                    <i class="fas fa-calendar-alt"></i>Jadwal
                </a>
                <a href="{{ route('tugas.index') }}" class="{{ request()->routeIs('tugas.*') ? 'active' : '' }}">
                    <i class="fas fa-tasks"></i>Tugas
                </a>
            </nav>

            <a href="{{ route('profil') }}" class="ms-auto text-white me-3"><i class="fas fa-user-circle fa-lg"></i></a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-link text-white p-0"><i class="fas fa-sign-out-alt fa-lg"></i></button>
            </form>
        </div>
    </header>

    <main class="container py-3">
        @yield('content')
    </main>

    {{-- Menu bawah berbasis ikon untuk mobile, menggantikan pola menu.php / panel_*.php lama --}}
    <nav class="bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fas fa-home"></i>Depan
        </a>
        <a href="{{ route('absensi.index') }}" class="{{ request()->routeIs('absensi.*') ? 'active' : '' }}">
            <i class="fas fa-clipboard-check"></i>Absensi
        </a>
        <a href="{{ route('jadwal.index') }}" class="{{ request()->routeIs('jadwal.*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i>Jadwal
        </a>
        <a href="{{ route('tugas.index') }}" class="{{ request()->routeIs('tugas.*') ? 'active' : '' }}">
            <i class="fas fa-tasks"></i>Tugas
        </a>
        <a href="{{ route('profil') }}" class="{{ request()->routeIs('profil') ? 'active' : '' }}">
            <i class="fas fa-user"></i>Profil
        </a>
    </nav>
</body>
</html>
