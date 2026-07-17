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
                ['label' => 'Laporan Keagamaan', 'icon' => 'fas fa-pray', 'color' => 'purple', 'href' => route('keagamaan.index')],
                ['label' => 'List Pelanggaran', 'icon' => 'fas fa-file-alt', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Upload RPP', 'icon' => 'fas fa-book', 'color' => 'amber', 'href' => route('rpp.upload')],
                ['label' => 'Peminjaman Ruang', 'icon' => 'fas fa-door-open', 'color' => 'teal', 'href' => route('smart.kalender')],
                ['label' => 'Nilai PSAJ Tulis', 'icon' => 'fas fa-book-open', 'color' => 'amber', 'href' => route('modul', 'nilai-psaj-tulis')],
                ['label' => 'Rekap Kehadiran', 'icon' => 'fas fa-clipboard-list', 'color' => 'green', 'href' => route('absensi.index')],
                ['label' => 'Daftar Nama Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'teal', 'href' => route('siswa.index')],
                ['label' => 'Lapor Kebersihan', 'icon' => 'fas fa-broom', 'color' => 'green', 'href' => route('kebersihan.kelas-grid')],
            ],
        ],
        'walikelas' => [
            'title' => 'Menu Wali Kelas',
            'theme' => 'pink',
            'items' => [
                ['label' => 'Aktivitas Kelas', 'icon' => 'fas fa-people-group', 'color' => 'pink', 'href' => route('aktivitas-kelas')],
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Pelanggaran Tatib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'DKN Kelas', 'icon' => 'fas fa-list-ol', 'color' => 'purple', 'href' => route('modul', 'dkn-kelas')],
            ],
        ],
        'kepsek' => [
            'title' => 'Menu Kepala Sekolah',
            'theme' => 'amber',
            'items' => [
                ['label' => 'Ajukan Guru', 'icon' => 'fas fa-user-plus', 'color' => 'green', 'href' => route('modul', 'ajukan-guru')],
                ['label' => 'List Ajuan Guru', 'icon' => 'fas fa-list', 'color' => 'blue', 'href' => route('modul', 'list-ajuan-guru')],
                ['label' => 'Pelanggaran Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Kehadiran Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('guru.index')],
                ['label' => 'Ketidakhadiran', 'icon' => 'fas fa-user-times', 'color' => 'red', 'href' => route('modul', 'ketidakhadiran')],
                ['label' => 'Rekap Absen Guru', 'icon' => 'fas fa-chart-bar', 'color' => 'blue', 'href' => route('modul', 'rekap-absen-guru')],
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
                ['label' => 'Siswa Terlambat', 'icon' => 'fas fa-clock', 'color' => 'red', 'href' => route('absensi.telat.list')],
                ['label' => 'Absensi Siswa', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Arsip Surat', 'icon' => 'fas fa-envelope-open-text', 'color' => 'amber', 'href' => route('modul', 'arsip-surat')],
                ['label' => 'Ajuan Absensi Masuk', 'icon' => 'fas fa-door-open', 'color' => 'purple', 'href' => route('ajuan-absensi.index')],
                ['label' => 'Absensi Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('guru.index')],
                // Guru absen -> guru upload tugas -> piket sampaikan ke siswa di kelas
                ['label' => 'Tugas Guru Absen', 'icon' => 'fas fa-clipboard-list', 'color' => 'pink', 'href' => route('modul', 'tugas-guru-absen')],
            ],
        ],
        'tatib' => [
            'title' => 'Menu Tata Tertib',
            'theme' => 'red',
            'items' => [
                ['label' => 'Lapor Pelanggaran', 'icon' => 'fas fa-plus', 'color' => 'red', 'href' => route('tatib.cari')],
                ['label' => 'List Pelanggaran', 'icon' => 'fas fa-clipboard-list', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Absensi Bulanan', 'icon' => 'fas fa-calendar-alt', 'color' => 'blue', 'href' => route('modul', 'absensi-bulanan')],
            ],
        ],
        'bk' => [
            'title' => 'Menu Bimbingan Konseling',
            'theme' => 'green',
            'items' => [
                ['label' => 'Tambah Catatan', 'icon' => 'fas fa-plus', 'color' => 'green', 'href' => route('bimbingan.cari')],
                ['label' => 'Data Bimbingan', 'icon' => 'fas fa-hands-helping', 'color' => 'pink', 'href' => route('bimbingan.index')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
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
    ];
@endphp

@section('content')
<div class="p-4 bg-white rounded shadow mb-4">
    <h1 class="h5 mb-1">Selamat datang, {{ $member->nama }}</h1>
    <p class="text-muted mb-0">Peran: {{ implode(', ', $member->roles()) ?: '-' }}</p>
</div>

@foreach ($panels as $role => $panel)
    @if ($member->hasRole($role))
        <x-menu-section :title="$panel['title']" :items="$panel['items']" :theme="$panel['theme']" />
    @endif
@endforeach
@endsection
