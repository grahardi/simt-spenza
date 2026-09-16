<?php

namespace App\Services;

/**
 * Definisi menu/fitur per role untuk dashboard - dipakai bersama oleh
 * dashboard.blade.php dan halaman Superadmin > Pengaturan Fitur, supaya
 * tidak ada 2 sumber kebenaran yang bisa beda-beda.
 */
class MenuPanelDefinisi
{
    public static function semua(): array
    {
    $panels = [
        'guru' => [
            'title' => 'Menu Jabatan Guru',
            'theme' => 'blue',
            'items' => [
                ['label' => 'Jadwal Mengajar', 'icon' => 'fas fa-clock', 'color' => 'blue', 'href' => route('jadwal-mengajar')],
                ['label' => 'Ajukan Absen Diri', 'icon' => 'fas fa-user-clock', 'color' => 'red', 'href' => route('ajuan-absen-guru.index')],
                ['label' => 'Guru Wali', 'icon' => 'fas fa-user-friends', 'color' => 'purple', 'href' => route('guru.wali-siswa')],
                ['label' => 'Ajuan Surat', 'icon' => 'fas fa-file-signature', 'color' => 'red', 'href' => route('ajuan-surat.index')],
                ['label' => 'Pelanggaran Keagamaan', 'icon' => 'fas fa-mosque', 'color' => 'coral', 'href' => route('pelanggaran-keagamaan.pilih-kelas')],
                ['label' => 'Upload Soal', 'icon' => 'fas fa-file-upload', 'color' => 'teal', 'href' => route('soal-upload.form')],
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
                ['label' => 'Rekap Absensi Mingguan', 'icon' => 'fas fa-calendar-week', 'color' => 'blue', 'href' => route('aktivitas-kelas.rekap-mingguan')],
                ['label' => 'Data Pelanggaran', 'icon' => 'fas fa-exclamation-circle', 'color' => 'red', 'href' => route('aktivitas-kelas.pelanggaran-siswa')],
                ['label' => 'Manajemen WhatsApp', 'icon' => 'fab fa-whatsapp', 'color' => 'green', 'href' => route('walikelas.whatsapp')],
                ['label' => 'Ajukan Bansos', 'icon' => 'fas fa-hand-holding-heart', 'color' => 'amber', 'href' => route('bansos.ajukan')],
                ['label' => 'Data PIP', 'icon' => 'fas fa-hand-holding-usd', 'color' => 'blue', 'href' => route('pip.index')],
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
                ['label' => 'Absen Guru', 'icon' => 'fas fa-chalkboard-teacher', 'color' => 'green', 'href' => route('absen-guru.index')],
            ],
        ],
        'tatib' => [
            'title' => 'Menu Tata Tertib',
            'theme' => 'red',
            'items' => [
                ['label' => 'Absensi Hari Ini', 'icon' => 'fas fa-clipboard-check', 'color' => 'blue', 'href' => route('absensi.index')],
                ['label' => 'Keterlambatan', 'icon' => 'fas fa-clock', 'color' => 'amber', 'href' => route('absensi.telat.list')],
                ['label' => 'Tidak Masuk 3+ Hari', 'icon' => 'fas fa-user-clock', 'color' => 'red', 'href' => route('kesiswaan.tidak-masuk')],
                ['label' => 'Tindak Lanjut', 'icon' => 'fas fa-plus', 'color' => 'coral', 'href' => route('tatib.cari')],
                ['label' => 'Pelanggaran', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('tatib.index')],
                ['label' => 'Rekap Absen Mingguan', 'icon' => 'fas fa-calendar-week', 'color' => 'purple', 'href' => route('kesiswaan.rekap-mingguan')],
                ['label' => 'Absensi Bulanan', 'icon' => 'fas fa-calendar-alt', 'color' => 'blue', 'href' => route('absensi-bulanan')],
                ['label' => 'Rekap Penerima Bansos', 'icon' => 'fas fa-hand-holding-heart', 'color' => 'amber', 'href' => route('bansos.rekap')],
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
                ['label' => 'Laporan/Rekap Sholat', 'icon' => 'fas fa-calendar-day', 'color' => 'purple', 'href' => route('keagamaan.rekap')],
                ['label' => 'Rekap Harian Ijin/Halangan/Kabur', 'icon' => 'fas fa-mosque', 'color' => 'red', 'href' => route('pelanggaran-keagamaan.rekap-harian')],
                ['label' => 'Rekap Terbanyak Ijin/Halangan/Kabur', 'icon' => 'fas fa-chart-bar', 'color' => 'amber', 'href' => route('pelanggaran-keagamaan.rekap-terbanyak')],
                ['label' => 'Aksi Pelanggaran', 'icon' => 'fas fa-gavel', 'color' => 'coral', 'href' => route('pelanggaran-keagamaan.aksi')],
            ],
        ],
        'adminsoal' => [
            'title' => 'Menu Admin Soal',
            'theme' => 'teal',
            'items' => [
                ['label' => 'Kelola Soal', 'icon' => 'fas fa-file-alt', 'color' => 'teal', 'href' => route('soal-upload.index')],
                ['label' => 'List Upload', 'icon' => 'fas fa-th-list', 'color' => 'blue', 'href' => route('soal-upload.list-upload')],
                ['label' => 'Kartu Ujian & Denah', 'icon' => 'fas fa-id-card', 'color' => 'purple', 'href' => route('kartu-ujian.index')],
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
                ['label' => 'Riwayat Siswa', 'icon' => 'fas fa-history', 'color' => 'purple', 'href' => route('uks.riwayat-siswa')],
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
                ['label' => 'Rekap Penerima Bansos', 'icon' => 'fas fa-hand-holding-heart', 'color' => 'amber', 'href' => route('bansos.rekap')],
                ['label' => 'Data PIP', 'icon' => 'fas fa-hand-holding-usd', 'color' => 'blue', 'href' => route('pip.index')],
            ],
        ],
        'admin_kegiatan' => [
            'title' => 'Menu Admin Kegiatan',
            'theme' => 'purple',
            'items' => [
                ['label' => 'Agenda', 'icon' => 'fas fa-calendar-alt', 'color' => 'purple', 'href' => route('agenda.index')],
                ['label' => 'List Agenda', 'icon' => 'fas fa-list', 'color' => 'blue', 'href' => route('agenda.list-sudah')],
                ['label' => 'Tambah Agenda', 'icon' => 'fas fa-plus', 'color' => 'green', 'href' => route('agenda.create')],
            ],
        ],
    ];

        return $panels;
    }
}
