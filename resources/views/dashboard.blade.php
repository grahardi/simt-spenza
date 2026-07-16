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
            'items' => [
                ['label' => 'Jadwal Mengajar', 'icon' => 'fas fa-clock', 'color' => 'blue', 'href' => route('modul', 'jadwal-mengajar')],
                ['label' => 'Laporan Keagamaan', 'icon' => 'fas fa-pray', 'color' => 'purple', 'href' => route('modul', 'laporan-keagamaan')],
                ['label' => 'List Pelanggaran', 'icon' => 'fas fa-file-alt', 'color' => 'coral', 'href' => route('modul', 'list-pelanggaran')],
                ['label' => 'Upload RPP', 'icon' => 'fas fa-book', 'color' => 'amber', 'href' => route('modul', 'upload-rpp')],
                ['label' => 'Smartboard', 'icon' => 'fas fa-chalkboard', 'color' => 'teal', 'href' => route('modul', 'smartboard')],
                ['label' => 'Nilai PSAJ Tulis', 'icon' => 'fas fa-book-open', 'color' => 'amber', 'href' => route('modul', 'nilai-psaj-tulis')],
                ['label' => 'Rekap Kehadiran', 'icon' => 'fas fa-clipboard-list', 'color' => 'green', 'href' => route('absensi.index')],
                ['label' => 'Daftar Nama Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'teal', 'href' => route('siswa.index')],
            ],
        ],
        'walikelas' => [
            'title' => 'Menu Wali Kelas',
            'items' => [
                ['label' => 'Aktivitas Kelas', 'icon' => 'fas fa-people-group', 'color' => 'pink', 'href' => route('modul', 'aktivitas-kelas')],
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Pelanggaran Tatib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('modul', 'pelanggaran-tatib')],
                ['label' => 'DKN Kelas', 'icon' => 'fas fa-list-ol', 'color' => 'purple', 'href' => route('modul', 'dkn-kelas')],
            ],
        ],
        'kepsek' => [
            'title' => 'Menu Kepala Sekolah',
            'items' => [
                ['label' => 'Ajukan Guru', 'icon' => 'fas fa-user-plus', 'color' => 'green', 'href' => route('modul', 'ajukan-guru')],
                ['label' => 'List Ajuan Guru', 'icon' => 'fas fa-list', 'color' => 'blue', 'href' => route('modul', 'list-ajuan-guru')],
                ['label' => 'Pelanggaran Siswa', 'icon' => 'fas fa-user-graduate', 'color' => 'coral', 'href' => route('modul', 'pelanggaran-siswa')],
                ['label' => 'Kehadiran Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('guru.index')],
                ['label' => 'Ketidakhadiran', 'icon' => 'fas fa-user-times', 'color' => 'red', 'href' => route('modul', 'ketidakhadiran')],
                ['label' => 'Rekap Absen Guru', 'icon' => 'fas fa-chart-bar', 'color' => 'blue', 'href' => route('modul', 'rekap-absen-guru')],
                ['label' => 'RPP Guru', 'icon' => 'fas fa-book', 'color' => 'amber', 'href' => route('modul', 'rpp-guru')],
            ],
        ],
        'admin' => [
            'title' => 'Menu Admin Absensi',
            'items' => [
                ['label' => 'Ajukan Absensi', 'icon' => 'fas fa-inbox', 'color' => 'purple', 'href' => route('ajuan-absensi.pilih-kelas')],
                ['label' => 'Isi Absensi', 'icon' => 'fas fa-pen', 'color' => 'blue', 'href' => route('absensi.isi')],
                ['label' => 'Absensi Siswa', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
            ],
        ],
        'piket' => [
            'title' => 'Menu Piket',
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
            'items' => [
                ['label' => 'Pelanggaran Absensi', 'icon' => 'fas fa-clipboard-list', 'color' => 'coral', 'href' => route('modul', 'pelanggaran-absensi')],
                ['label' => 'Pelanggaran KBM', 'icon' => 'fas fa-chalkboard', 'color' => 'red', 'href' => route('modul', 'pelanggaran-kbm')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('modul', 'pelanggaran-tata-tertib')],
                ['label' => 'Absensi Bulanan', 'icon' => 'fas fa-calendar-alt', 'color' => 'blue', 'href' => route('modul', 'absensi-bulanan')],
            ],
        ],
        'bk' => [
            'title' => 'Menu Bimbingan Konseling',
            'items' => [
                ['label' => 'Pelanggaran Kedisiplinan', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'red', 'href' => route('modul', 'pelanggaran-kedisiplinan')],
                ['label' => 'Pendampingan', 'icon' => 'fas fa-hands-helping', 'color' => 'pink', 'href' => route('modul', 'pendampingan')],
                ['label' => 'Pelanggaran Tata Tertib', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('modul', 'pelanggaran-tata-tertib-bk')],
            ],
        ],
        'keagamaan' => [
            'title' => 'Menu Keagamaan',
            'items' => [
                ['label' => 'Laporan Hari Ini', 'icon' => 'fas fa-calendar-day', 'color' => 'purple', 'href' => route('modul', 'laporan-hari-ini')],
                ['label' => 'Rekap Pelanggar', 'icon' => 'fas fa-list', 'color' => 'coral', 'href' => route('modul', 'rekap-pelanggar')],
                ['label' => 'Tindakan', 'icon' => 'fas fa-gavel', 'color' => 'red', 'href' => route('modul', 'tindakan')],
            ],
        ],
        'kebersihan' => [
            'title' => 'Menu Kebersihan',
            'items' => [
                ['label' => 'List Laporan', 'icon' => 'fas fa-list', 'color' => 'teal', 'href' => route('modul', 'list-laporan-kebersihan')],
                ['label' => 'Entry Laporan', 'icon' => 'fas fa-pen', 'color' => 'green', 'href' => route('modul', 'entry-laporan-kebersihan')],
                ['label' => 'Galeri', 'icon' => 'fas fa-images', 'color' => 'pink', 'href' => route('modul', 'galeri-kebersihan')],
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
