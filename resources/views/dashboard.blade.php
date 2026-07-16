@extends('layouts.app')

@section('title', 'Depan')

@php
    $member = auth('member')->user();

    // Pengganti depan.php lama: setiap role yang dimiliki user memunculkan
    // satu grup menu (dulu: include panel_guru.php, panel_kepsek.php, dst -
    // bisa tampil lebih dari satu sekaligus kalau user punya banyak role).
    $panels = [
        'guru' => [
            'title' => 'Menu Jabatan Guru',
            'items' => [
                ['label' => 'Jadwal Mengajar', 'icon' => 'fas fa-clock', 'href' => route('modul', 'jadwal-mengajar')],
                ['label' => 'Laporan Keagamaan', 'icon' => 'fas fa-pray', 'href' => route('modul', 'laporan-keagamaan')],
                ['label' => 'List Pelanggaran', 'icon' => 'fas fa-file-alt', 'href' => route('modul', 'list-pelanggaran')],
                ['label' => 'Upload RPP', 'icon' => 'fas fa-book', 'href' => route('modul', 'upload-rpp')],
                ['label' => 'Smartboard', 'icon' => 'fas fa-chalkboard', 'href' => route('modul', 'smartboard')],
                ['label' => 'Nilai PSAJ Tulis', 'icon' => 'fas fa-book-open', 'href' => route('modul', 'nilai-psaj-tulis')],
                ['label' => 'Rekap Kehadiran', 'icon' => 'fas fa-clipboard-list', 'href' => route('absensi.index')],
                ['label' => 'Daftar Nama Siswa', 'icon' => 'fas fa-user-graduate', 'href' => route('modul', 'daftar-nama-siswa')],
            ],
        ],
        'walikelas' => [
            'title' => 'Menu Wali Kelas',
            'items' => [
                ['label' => 'Aktivitas Kelas', 'icon' => 'fas fa-people-group', 'href' => route('modul', 'aktivitas-kelas')],
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-check', 'href' => route('absensi.index')],
                ['label' => 'Pelanggaran Tatib', 'icon' => 'fas fa-gavel', 'href' => route('modul', 'pelanggaran-tatib')],
                ['label' => 'DKN Kelas', 'icon' => 'fas fa-list-ol', 'href' => route('modul', 'dkn-kelas')],
            ],
        ],
        'kepsek' => [
            'title' => 'Menu Kepala Sekolah',
            'items' => [
                ['label' => 'Ajukan Guru', 'icon' => 'fas fa-user-plus', 'href' => route('modul', 'ajukan-guru')],
                ['label' => 'List Ajuan Guru', 'icon' => 'fas fa-list', 'href' => route('modul', 'list-ajuan-guru')],
                ['label' => 'Pelanggaran Siswa', 'icon' => 'fas fa-user-graduate', 'href' => route('modul', 'pelanggaran-siswa')],
                ['label' => 'Kehadiran Guru', 'icon' => 'fas fa-chalkboard-teacher', 'href' => route('modul', 'kehadiran-guru')],
                ['label' => 'Ketidakhadiran', 'icon' => 'fas fa-user-times', 'href' => route('modul', 'ketidakhadiran')],
                ['label' => 'Rekap Absen Guru', 'icon' => 'fas fa-chart-bar', 'href' => route('modul', 'rekap-absen-guru')],
                ['label' => 'RPP Guru', 'icon' => 'fas fa-book', 'href' => route('modul', 'rpp-guru')],
            ],
        ],
        'admin' => [
            'title' => 'Menu Admin Absensi',
            'items' => [
                ['label' => 'Ajuan Absensi Siswa', 'icon' => 'fas fa-inbox', 'href' => route('modul', 'ajuan-absensi-siswa')],
                ['label' => 'Absensi Siswa', 'icon' => 'fas fa-clipboard-check', 'href' => route('absensi.index')],
                ['label' => 'List Ajuan', 'icon' => 'fas fa-list', 'href' => route('modul', 'list-ajuan')],
            ],
        ],
        'piket' => [
            'title' => 'Menu Piket',
            'items' => [
                ['label' => 'Siswa Terlambat', 'icon' => 'fas fa-clock', 'href' => route('modul', 'siswa-terlambat')],
                ['label' => 'Absensi Siswa', 'icon' => 'fas fa-clipboard-check', 'href' => route('absensi.index')],
                ['label' => 'Arsip Surat', 'icon' => 'fas fa-envelope-open-text', 'href' => route('modul', 'arsip-surat')],
                ['label' => 'Ajuan Absensi Masuk', 'icon' => 'fas fa-door-open', 'href' => route('modul', 'ajuan-absensi-masuk')],
                ['label' => 'Absensi Guru', 'icon' => 'fas fa-chalkboard-teacher', 'href' => route('modul', 'absensi-guru')],
                ['label' => 'Ubah Absensi', 'icon' => 'fas fa-edit', 'href' => route('modul', 'ubah-absensi')],
            ],
        ],
        'tatib' => [
            'title' => 'Menu Tata Tertib',
            'items' => [
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-list', 'href' => route('modul', 'pelanggaran-absensi')],
                ['label' => 'Pelanggaran KBM', 'icon' => 'fas fa-chalkboard', 'href' => route('modul', 'pelanggaran-kbm')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'href' => route('modul', 'pelanggaran-tata-tertib')],
                ['label' => 'Absensi Bulanan', 'icon' => 'fas fa-calendar-alt', 'href' => route('modul', 'absensi-bulanan')],
            ],
        ],
        'bk' => [
            'title' => 'Menu Bimbingan Konseling',
            'items' => [
                ['label' => 'Pelanggaran Kedisiplinan', 'icon' => 'fas fa-exclamation-triangle', 'href' => route('modul', 'pelanggaran-kedisiplinan')],
                ['label' => 'Pendampingan', 'icon' => 'fas fa-hands-helping', 'href' => route('modul', 'pendampingan')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'href' => route('modul', 'pelanggaran-tata-tertib-bk')],
            ],
        ],
        'keagamaan' => [
            'title' => 'Menu Keagamaan',
            'items' => [
                ['label' => 'Laporan Hari Ini', 'icon' => 'fas fa-calendar-day', 'href' => route('modul', 'laporan-hari-ini')],
                ['label' => 'Rekap Pelanggar', 'icon' => 'fas fa-list', 'href' => route('modul', 'rekap-pelanggar')],
                ['label' => 'Tindakan', 'icon' => 'fas fa-gavel', 'href' => route('modul', 'tindakan')],
            ],
        ],
        'kebersihan' => [
            'title' => 'Menu Kebersihan',
            'items' => [
                ['label' => 'List Laporan', 'icon' => 'fas fa-list', 'href' => route('modul', 'list-laporan-kebersihan')],
                ['label' => 'Entry Laporan', 'icon' => 'fas fa-pen', 'href' => route('modul', 'entry-laporan-kebersihan')],
                ['label' => 'Galeri', 'icon' => 'fas fa-images', 'href' => route('modul', 'galeri-kebersihan')],
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
        <x-menu-section :title="$panel['title']" :items="$panel['items']" />
    @endif
@endforeach
@endsection
