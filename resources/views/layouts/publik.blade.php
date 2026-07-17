<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Jadwal') - SIMT SMP Negeri 1 Turen</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        body { background: #f4f5f7; padding-bottom: 24px; }

        .kelas-grid { display: grid; grid-template-columns: repeat(5, 1fr); gap: 10px 6px; }
        .kelas-btn {
            display: flex; align-items: center; justify-content: center;
            width: 70px; height: 70px; justify-self: center;
            border-radius: 14px; font-weight: 700; font-size: 20px;
            text-decoration: none; color: #fff;
        }
        .kelas-7 { background: #1abc9c; }
        .kelas-8 { background: #c9971f; }
        .kelas-9 { background: #c0392b; }
        .kelas-lain { background: #6c757d; }
        @media (max-width: 576px) {
            .kelas-grid { gap: 8px 4px; }
            .kelas-btn { width: 56px; height: 56px; font-size: 16px; border-radius: 10px; }
        }

        .jadwal-baris {
            display: flex; align-items: center; gap: 12px;
            border-radius: 10px; padding: 10px 14px; color: inherit;
        }
        .jadwal-jam-kecil {
            width: 26px; height: 26px; border-radius: 50%;
            background: rgba(255,255,255,.6); color: inherit; font-weight: 700; font-size: 13px;
            display: flex; align-items: center; justify-content: center; flex-shrink: 0;
        }
        .jadwal-waktu-kecil { font-size: 12px; width: 105px; flex-shrink: 0; font-weight: 600; opacity: .85; }
        .jadwal-kelas-kecil { font-weight: 700; width: 70px; flex-shrink: 0; }
        .jadwal-mapel-kecil { flex: 1; font-weight: 600; }
        @media (max-width: 576px) {
            .jadwal-baris { flex-wrap: wrap; }
            .jadwal-waktu-kecil { width: auto; order: 3; font-size: 11px; }
        }

        .bg-blue   { background: #d3e9fb; color: #185fa5; }
        .bg-teal   { background: #c9f0e2; color: #0f6e56; }
        .bg-coral  { background: #f8ddd1; color: #993c1d; }
        .bg-pink   { background: #f9d9e5; color: #993556; }
        .bg-amber  { background: #faedc9; color: #854f0b; }
        .bg-green  { background: #ddedc8; color: #3b6d11; }
        .bg-purple { background: #e0dbfc; color: #534ab7; }
        .bg-red    { background: #f9d4d2; color: #a32d2d; }

        .badge-status { display: inline-flex; align-items: center; border-radius: 999px; padding: 4px 12px; font-size: 12px; font-weight: 600; }

        .menu-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
        .menu-card {
            border-radius: 16px; padding: 18px 6px 14px; text-align: center;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            text-decoration: none; color: inherit; border: 1px solid rgba(0,0,0,.03);
            box-shadow: 0 4px 6px rgba(0,0,0,0.06); transition: transform .15s ease, box-shadow .15s ease;
        }
        .menu-card:hover { transform: translateY(-2px); box-shadow: 0 7px 14px rgba(0,0,0,0.12); text-decoration: none; color: inherit; }
        .menu-icon {
            width: 52px; height: 52px; border-radius: 50%; background: rgba(255,255,255,.55);
            display: flex; align-items: center; justify-content: center; margin-bottom: 8px; font-size: 22px; color: inherit;
        }
        .menu-title { font-weight: 700; font-size: 13px; line-height: 1.2; color: inherit; }
    </style>
</head>
<body>
    <header class="text-white p-3 shadow mb-3" style="background:linear-gradient(135deg,#1a1030,#4b0082);">
        <div class="container d-flex align-items-center gap-2">
            <img src="{{ asset('images/logo-smpn1turen.jpg') }}" alt="Logo" style="width:36px;height:36px;border-radius:50%;object-fit:cover;background:#fff;">
            <div>
                <div class="fw-bold" style="font-size:15px;">SMP Negeri 1 Turen</div>
                <div class="small opacity-75">Jadwal Pelajaran - Halaman Publik</div>
            </div>
            <a href="{{ route('login') }}" class="btn btn-light btn-sm ms-auto">
                <i class="fas fa-sign-in-alt me-1"></i> Login
            </a>
        </div>
    </header>

    <main class="container">
        @yield('content')
    </main>
</body>
</html>
