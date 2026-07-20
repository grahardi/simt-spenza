<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #111; line-height: 1.5; }
        .kop { text-align: center; border-bottom: 3px double #000; padding-bottom: 8px; margin-bottom: 16px; }
        .kop h2 { margin: 0; font-size: 15px; }
        .kop p { margin: 1px 0; font-size: 10px; }
        h3.judul { text-align: center; text-decoration: underline; margin: 0 0 2px; font-size: 14px; }
        .nomor { text-align: center; margin: 0 0 16px; font-size: 12px; }
        table.isi { width: 100%; border-collapse: collapse; margin-bottom: 10px; }
        table.isi td { vertical-align: top; padding: 2px 4px; }
        .label-kolom { width: 130px; }
        .titik-dua { width: 15px; }
        .ttd-blok { margin-top: 40px; text-align: center; }
        .page-break { page-break-before: always; }
        table.spd { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.spd td, table.spd th { border: 1px solid #333; padding: 5px; font-size: 11px; vertical-align: top; }
    </style>
</head>
<body>

{{-- ============ HALAMAN 1: SURAT TUGAS ============ --}}
<div class="kop">
    <h2>DINAS PENDIDIKAN</h2>
    <h2>SMP NEGERI 1 TUREN</h2>
    <p>Jalan Panglima Sudirman Nomor 1A, Kecamatan Turen, Kabupaten Malang 65175</p>
    <p>Telepon (0341) 824031; Pos-el email@smpn1turen.sch.id &middot; Laman: www.smpn1turen.sch.id</p>
</div>

<h3 class="judul">SURAT TUGAS</h3>
<p class="nomor">Nomor: {{ $nomorSurat }}</p>

<table class="isi">
    <tr>
        <td class="label-kolom">Dasar</td>
        <td class="titik-dua">:</td>
        <td>{{ $data['dasar'] ?? '-' }}</td>
    </tr>
</table>

<p><strong>MEMERINTAHKAN:</strong></p>

<table class="isi">
    <tr>
        <td class="label-kolom">Kepada</td>
        <td class="titik-dua">:</td>
        <td>Nama</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->nama }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>NIP</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->nip ?? '-' }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>Pangkat/Golongan</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->member->pangkat ?? '-' }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>Jabatan</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->member->jabatan_dinas ?? '-' }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>Unit Kerja</td>
        <td class="titik-dua">:</td>
        <td>SMP Negeri 1 Turen</td>
    </tr>
</table>

<table class="isi">
    <tr>
        <td class="label-kolom">Untuk</td>
        <td class="titik-dua">:</td>
        <td>
            Menghadiri {{ $data['maksud'] ?? 'kegiatan tersebut' }} yang dilaksanakan pada hari
            {{ $data['hari'] ?? '-' }} pukul {{ $data['jam_mulai'] ?? '-' }} WIB
            s.d. {{ $data['jam_selesai'] ?? 'selesai' }} bertempat di {{ $data['tempat'] ?? '-' }},
            dengan tema {{ $data['tema'] ?? '-' }}.
        </td>
    </tr>
</table>

<p>Sesuai prosedur, setelah melaksanakan kegiatan dimaksud agar melaporkan hasilnya kepada Pimpinan.</p>
<p>Demikian surat tugas ini dibuat untuk dilaksanakan dengan penuh tanggung jawab.</p>

<div class="ttd-blok" style="text-align:right; margin-right:40px;">
    <p>Turen, {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}</p>
    <p>Kepala SMP Negeri 1 Turen</p>
    <br><br><br>
    <p><strong>{{ $pengaturan->kepsek_nama ?? '-' }}</strong></p>
    <p>{{ $pengaturan->kepsek_pangkat ?? '-' }}</p>
    <p>NIP {{ $pengaturan->kepsek_nip ?? '-' }}</p>
</div>

{{-- ============ HALAMAN 2: SPD ============ --}}
<div class="page-break"></div>

<div class="kop">
    <h2>DINAS PENDIDIKAN</h2>
    <h2>SMP NEGERI 1 TUREN</h2>
    <p>Jalan Panglima Sudirman Nomor 1A, Kecamatan Turen, Kabupaten Malang 65175</p>
</div>

<h3 class="judul">SURAT PERJALANAN DINAS (SPD)</h3>
<p class="nomor">Nomor: {{ $nomorSurat }}</p>

<table class="spd">
    <tr><td width="25">1.</td><td width="260">Pengguna Anggaran/Kuasa Pengguna Anggaran/Pejabat Pembuat Komitmen</td><td>{{ $pengaturan->kepsek_nama ?? '-' }}</td></tr>
    <tr><td>2.</td><td>Nama/NIP Pegawai yang melaksanakan perjalanan dinas</td><td>{{ $guru->nama }} / {{ $guru->nip ?? '-' }}</td></tr>
    <tr><td>3.</td><td>a. Pangkat dan Golongan<br>b. Jabatan/Instansi</td><td>{{ $guru->member->pangkat ?? '-' }}<br>{{ $guru->member->jabatan_dinas ?? '-' }} / SMP Negeri 1 Turen</td></tr>
    <tr><td>4.</td><td>Maksud Perjalanan Dinas</td><td>{{ $data['maksud'] ?? '-' }}</td></tr>
    <tr><td>5.</td><td>Alat Angkut yang dipergunakan</td><td>Perjalanan darat</td></tr>
    <tr><td>6.</td><td>a. Tempat Berangkat<br>b. Tempat Tujuan</td><td>SMP Negeri 1 Turen<br>{{ $data['tempat_tujuan'] ?? '-' }}</td></tr>
    <tr>
        <td>7.</td>
        <td>a. Lamanya Perjalanan Dinas<br>b. Tanggal Berangkat<br>c. Tanggal Kembali</td>
        <td>
            {{ $data['total_hari'] ?? 1 }} hari<br>
            {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}<br>
            {{ isset($data['tanggal_selesai']) && $data['tanggal_selesai'] ? \Carbon\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d F Y') : '-' }}
        </td>
    </tr>
    <tr><td>8.</td><td>Pembebanan Anggaran</td><td>BOS Sekolah</td></tr>
    <tr><td>9.</td><td>Keterangan lain-lain</td><td>-</td></tr>
</table>

<div class="ttd-blok" style="text-align:right; margin-right:40px;">
    <p>Turen, {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}</p>
    <p>Kepala Sekolah</p>
    <br><br><br>
    <p><strong>{{ $pengaturan->kepsek_nama ?? '-' }}</strong></p>
    <p>NIP {{ $pengaturan->kepsek_nip ?? '-' }}</p>
</div>

</body>
</html>
