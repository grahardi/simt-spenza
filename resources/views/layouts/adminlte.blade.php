<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Superadmin') - SIMT</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        .small-box .icon { opacity: .35; }
        .content-wrapper { background: #f4f6f9; }
    </style>
    @stack('styles')
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
            <li class="nav-item d-none d-sm-inline-block">
                <a href="{{ route('dashboard') }}" class="nav-link"><i class="fas fa-arrow-left me-1"></i> Keluar Superadmin</a>
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
                <span class="nav-link">{{ auth('member')->user()->nama }}</span>
            </li>
            <li class="nav-item">
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link" style="border:none;background:none;">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <a href="{{ route('superadmin.dashboard') }}" class="brand-link">
            <span class="brand-text font-weight-light ms-2">SIMT Superadmin</span>
        </a>

        <div class="sidebar">
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    <li class="nav-item">
                        <a href="{{ route('superadmin.dashboard') }}" class="nav-link {{ request()->routeIs('superadmin.dashboard') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-tachometer-alt"></i>
                            <p>Dashboard</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.siswa.index') }}" class="nav-link {{ request()->routeIs('superadmin.siswa.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-graduate"></i>
                            <p>Data Siswa</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ request()->routeIs('superadmin.guru.*', 'superadmin.karyawan.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('superadmin.guru.*', 'superadmin.karyawan.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chalkboard-teacher"></i>
                            <p>
                                Guru dan Karyawan
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('superadmin.guru.index') }}" class="nav-link {{ request()->routeIs('superadmin.guru.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Guru</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('superadmin.karyawan.index') }}" class="nav-link {{ request()->routeIs('superadmin.karyawan.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Karyawan</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.kelas.index') }}" class="nav-link {{ request()->routeIs('superadmin.kelas.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-door-closed"></i>
                            <p>Data Kelas</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.akun.index') }}" class="nav-link {{ request()->routeIs('superadmin.akun.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-user-shield"></i>
                            <p>Kelola Akun</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.absensi.index') }}" class="nav-link {{ request()->routeIs('superadmin.absensi.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-check"></i>
                            <p>Data Absensi</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.pelanggaran.index') }}" class="nav-link {{ request()->routeIs('superadmin.pelanggaran.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-gavel"></i>
                            <p>Data Pelanggaran</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('superadmin.bk.index') }}" class="nav-link {{ request()->routeIs('superadmin.bk.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-hands-helping"></i>
                            <p>Data Bimbingan Konseling</p>
                        </a>
                    </li>
                    <li class="nav-item has-treeview {{ request()->routeIs('superadmin.log.*', 'superadmin.log-login.*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('superadmin.log.*', 'superadmin.log-login.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-history"></i>
                            <p>
                                Log
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('superadmin.log.index') }}" class="nav-link {{ request()->routeIs('superadmin.log.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Aktivitas</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('superadmin.log-login.index') }}" class="nav-link {{ request()->routeIs('superadmin.log-login.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Log Login</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li class="nav-item has-treeview {{ request()->routeIs('superadmin.whatsapp-*') ? 'menu-open' : '' }}">
                        <a href="#" class="nav-link {{ request()->routeIs('superadmin.whatsapp-*') ? 'active' : '' }}">
                            <i class="nav-icon fab fa-whatsapp"></i>
                            <p>
                                WhatsApp
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">
                            <li class="nav-item">
                                <a href="{{ route('superadmin.whatsapp-menu.index') }}" class="nav-link {{ request()->routeIs('superadmin.whatsapp-menu.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Menu Bot WhatsApp</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('superadmin.whatsapp-template.index') }}" class="nav-link {{ request()->routeIs('superadmin.whatsapp-template.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Template Balasan Bot</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('superadmin.whatsapp-nomor.index') }}" class="nav-link {{ request()->routeIs('superadmin.whatsapp-nomor.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Nomor WA Terdaftar (Siswa)</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('superadmin.whatsapp-guru.index') }}" class="nav-link {{ request()->routeIs('superadmin.whatsapp-guru.*') ? 'active' : '' }}">
                                    <i class="far fa-circle nav-icon"></i>
                                    <p>Nomor WA Terdaftar (Guru)</p>
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>
        </div>
    </aside>

    <div class="content-wrapper">
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0">@yield('title', 'Superadmin')</h1>
                    </div>
                </div>
            </div>
        </div>

        <section class="content">
            <div class="container-fluid">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @yield('content')
            </div>
        </section>
    </div>

    <footer class="main-footer">
        <strong>SIMT SMP Negeri 1 Turen</strong> &mdash; Panel Superadmin
    </footer>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
