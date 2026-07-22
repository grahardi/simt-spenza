@extends('layouts.app')

@section('title', 'Depan')

@php
    $member = auth('member')->user();

    // Pengganti depan.php lama: setiap role yang dimiliki user memunculkan
    // satu grup menu (dulu: include panel_guru.php, panel_kepsek.php, dst -
    // bisa tampil lebih dari satu sekaligus kalau user punya banyak role).
    // Warna dikelompokkan per kategori: biru = absensi, teal = data master,
    // amber = dokumen/RPP, coral/merah = pelanggaran/laporan, hijau = kehadiran,
    // pink/ungu = lainnya.
    $panels = [
        'guru' => [
            'title' => 'Menu Jabatan Guru',
            'theme' => 'blue',
            'items' => [
                ['label' => 'Jadwal Mengajar', 'icon' => 'fas fa-clock', 'color' => 'blue', 'href' => route('jadwal-mengajar')],
                ['label' => 'Ajukan Absen Diri', 'icon' => 'fas fa-user-clock', 'color' => 'red', 'href' => route('ajuan-absen-guru.index')],
                ['label' => 'Guru Wali', 'icon' => 'fas fa-user-friends', 'color' => 'purple', 'href' => route('guru.wali-siswa')],
                ['label' => 'Ajuan Surat', 'icon' => 'fas fa-file-signature', 'color' => 'red', 'href' => route('ajuan-surat.index')],
                ['label' => 'Laporan Keagamaan', 'icon' => 'fas fa-pray', 'color' => 'purple', 'href' => route('keagamaan.index')],
                ['label' => 'Peminjaman', 'icon' => 'fas fa-door-open', 'color' => 'teal', 'href' => route('smart.kalender')],
                ['label' => 'Daftar Nama Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'teal', 'href' => route('siswa.index')],
                ['label' => 'Foto Siswa', 'icon' => 'fas fa-images', 'color' => 'pink', 'href' => route('foto-siswa.pilih-kelas')],
            ],
        ],
        'walikelas' => [
            'title' => 'Menu Wali Kelas',
            'theme' => 'pink',
            'items' => [
                ['label' => 'Aktivitas Kelas', 'icon' => 'fas fa-people-group', 'color' => 'pink', 'href' => route('aktivitas-kelas')],
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Pelanggaran Tatib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Manajemen WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => 'green', 'href' => route('walikelas.whatsapp')],
            ],
        ],
        'kepsek' => [
            'title' => 'Menu Kepala Sekolah',
            'theme' => 'amber',
            'items' => [
                ['label' => 'Ajukan Guru', 'icon' => 'fas fa-user-plus', 'color' => 'green', 'href' => route('ajuan-guru.form')],
                ['label' => 'List Ajuan Guru', 'icon' => 'fas fa-list', 'color' => 'blue', 'href' => route('ajuan-guru.list')],
                ['label' => 'Pelanggaran Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Kehadiran Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('guru.index')],
                ['label' => 'Ketidakhadiran', 'icon' => 'fas fa-user-times', 'color' => 'red', 'href' => route('ajuan-guru.list')],
                ['label' => 'Rekap Absen Guru', 'icon' => 'fas fa-chart-bar', 'color' => 'blue', 'href' => route('ajuan-guru.list')],
                ['label' => 'RPP Guru', 'icon' => 'fas fa-book', 'color' => 'amber', 'href' => route('rpp.semua')],
            ],
        ],
        'admin' => [
            'title' => 'Menu Admin Absensi',
            'theme' => 'purple',
            'items' => [
                ['label' => 'Ajukan Absensi', 'icon' => 'fas fa-inbox', 'color' => 'purple', 'href' => route('ajuan-absensi.pilih-kelas')],
                ['label' => 'List Ajuan', 'icon' => 'fas fa-list', 'color' => 'teal', 'href' => route('ajuan-absensi.list')],
                ['label' => 'Siswa Absen Hari Ini', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
            ],
        ],
        'piket' => [
            'title' => 'Menu Piket',
            'theme' => 'teal',
            'items' => [
                ['label' => 'Isi Absensi', 'icon' => 'fas fa-pen', 'color' => 'blue', 'href' => route('absensi.isi')],
                ['label' => 'Isi Keterlambatan', 'icon' => 'fas fa-clock', 'color' => 'purple', 'href' => route('absensi.telat-isi')],
                ['label' => 'Siswa Terlambat', 'icon' => 'fas fa-clock', 'color' => 'red', 'href' => route('absensi.telat.list')],
                ['label' => 'Absensi Siswa', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Arsip Surat', 'icon' => 'fas fa-envelope-open-text', 'color' => 'amber', 'href' => route('arsip-surat')],
                ['label' => 'Ajuan Absensi Masuk', 'icon' => 'fas fa-door-open', 'color' => 'purple', 'href' => route('ajuan-absensi.index')],
                ['label' => 'Ajuan WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => 'green', 'href' => route('ajuan-whatsapp.index')],
                ['label' => 'Absensi Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('guru.absen-list')],
                // Guru absen -> guru upload tugas -> piket sampaikan ke siswa di kelas
                ['label' => 'Tugas Guru Absen', 'icon' => 'fas fa-clipboard-list', 'color' => 'pink', 'href' => route('guru.absen-list')],
            ],
        ],
        'tatib' => [
            'title' => 'Menu Tata Tertib',
            'theme' => 'red',
            'items' => [
                ['label' => 'Lapor Pelanggaran', 'icon' => 'fas fa-plus', 'color' => 'red', 'href' => route('tatib.cari')],
                ['label' => 'List Pelanggaran', 'icon' => 'fas fa-clipboard-list', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Absensi Bulanan', 'icon' => 'fas fa-calendar-alt', 'color' => 'blue', 'href' => route('absensi-bulanan')],
            ],
        ],
        'bk' => [
            'title' => 'Menu Bimbingan Konseling',
            'theme' => 'green',
            'items' => [
                ['label' => 'Tambah Catatan', 'icon' => 'fas fa-plus', 'color' => 'green', 'href' => route('bimbingan.cari')],
                ['label' => 'Data Bimbingan', 'icon' => 'fas fa-hands-helping', 'color' => 'pink', 'href' => route('bimbingan.index')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Absensi Hari Ini', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Rekap Absen Mingguan', 'icon' => 'fas fa-calendar-week', 'color' => 'purple', 'href' => route('kesiswaan.rekap-mingguan')],
            ],
        ],
        'keagamaan' => [
            'title' => 'Menu Keagamaan',
            'theme' => 'coral',
            'items' => [
                ['label' => 'Laporan Hari Ini', 'icon' => 'fas fa-calendar-day', 'color' => 'purple', 'href' => route('keagamaan.rekap')],
                ['label' => 'Rekap Pelanggar', 'icon' => 'fas fa-list', 'color' => 'coral', 'href' => route('keagamaan.rekap')],
            ],
        ],
        'kebersihan' => [
            'title' => 'Menu Kebersihan',
            'theme' => 'teal',
            'items' => [
                ['label' => 'List Laporan', 'icon' => 'fas fa-list', 'color' => 'teal', 'href' => route('kebersihan.index')],
                ['label' => 'Tambah Laporan', 'icon' => 'fas fa-pen', 'color' => 'green', 'href' => route('kebersihan.kelas-grid')],
                ['label' => 'Galeri', 'icon' => 'fas fa-images', 'color' => 'pink', 'href' => route('kebersihan.galeri')],
            ],
        ],
        'tata_usaha' => [
            'title' => 'Menu Tata Usaha',
            'theme' => 'blue',
            'items' => [
                ['label' => 'Surat Masuk', 'icon' => 'fas fa-inbox', 'color' => 'blue', 'href' => route('surat-masuk.index')],
                ['label' => 'Surat Keluar', 'icon' => 'fas fa-paper-plane', 'color' => 'purple', 'href' => route('surat-keluar.index')],
                ['label' => 'Kategori Surat', 'icon' => 'fas fa-tags', 'color' => 'teal', 'href' => route('kategori-surat.index')],
                ['label' => 'Ajuan Surat', 'icon' => 'fas fa-file-signature', 'color' => 'red', 'href' => route('surat-tu.index')],
                ['label' => 'Surat Permohonan', 'icon' => 'fas fa-file-alt', 'color' => 'amber', 'href' => route('surat-tu.permohonan.create')],
            ],
        ],
        'uks' => [
            'title' => 'Menu UKS',
            'theme' => 'coral',
            'items' => [
                ['label' => 'Siswa Sakit', 'icon' => 'fas fa-briefcase-medical', 'color' => 'red', 'href' => route('uks.cari')],
                ['label' => 'Siswa di UKS', 'icon' => 'fas fa-bed', 'color' => 'amber', 'href' => route('uks.list')],
                ['label' => 'Panggilan Wali Murid', 'icon' => 'fas fa-phone-alt', 'color' => 'green', 'href' => route('uks.panggilan')],
            ],
        ],
        'kesiswaan' => [
            'title' => 'Menu Kesiswaan',
            'theme' => 'green',
            'items' => [
                ['label' => 'Absensi Hari Ini', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Keterlambatan', 'icon' => 'fas fa-clock', 'color' => 'amber', 'href' => route('absensi.telat.list')],
                ['label' => 'Tidak Masuk 3+ Hari', 'icon' => 'fas fa-user-clock', 'color' => 'red', 'href' => route('kesiswaan.tidak-masuk')],
                ['label' => 'Pelanggaran', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Rekap Absen Mingguan', 'icon' => 'fas fa-calendar-week', 'color' => 'purple', 'href' => route('kesiswaan.rekap-mingguan')],
            ],
        ],
    ];
@endphp

@section('content')
<div class="p-4 bg-white rounded shadow mb-4">
    <h1 class="h5 mb-1">Selamat datang, {{ $member->nama }}</h1>
    <p class="text-muted mb-0">Peran: {{ implode(', ', $member->roles()) ?: '-' }}</p>
</div>

@if ($member->hasRole('superadmin'))
    <div class="p-4 rounded shadow mb-4 text-white" style="background:linear-gradient(135deg,#1a1030,#4b0082);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h3 class="h6 mb-1"><i class="fas fa-user-shield me-2"></i>Panel Superadmin</h3>
                <p class="mb-0 small opacity-75">Kelola data siswa, guru & roles, absensi, pelanggaran, dan bimbingan konseling secara penuh.</p>
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-right me-1"></i> Buka Panel Superadmin
            </a>
        </div>
    </div>
@endif

@foreach ($panels as $role => $panel)
    @if ($member->hasRole($role))
        <x-menu-section :title="$panel['title']" :items="$panel['items']" :theme="$panel['theme']" />
    @endif
@endforeach
@endsection
