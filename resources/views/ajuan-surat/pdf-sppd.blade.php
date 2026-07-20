<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #000; line-height: 1.4; }
        .kop { text-align: center; margin-bottom: 6px; }
        .kop-wrap { position: relative; }
        .kop-logo { position: absolute; left: 0; top: 0; width: 75px; }
        .kop h2 { margin: 0; font-size: 14px; }
        .kop h1 { margin: 2px 0; font-size: 16px; }
        .kop p { margin: 1px 0; font-size: 10px; }
        .garis-bawah { border-bottom: 3px double #000; margin-bottom: 14px; }
        h3.judul { text-align: center; text-decoration: underline; margin: 0 0 2px; font-size: 14px; }
        .nomor { text-align: center; margin: 0 0 16px; font-size: 12px; }
        table.isi { width: 100%; border-collapse: collapse; margin-bottom: 8px; }
        table.isi td { vertical-align: top; padding: 2px 4px; }
        .label-kolom { width: 90px; }
        .label-kolom2 { width: 110px; }
        .titik-dua { width: 12px; }
        .ttd-blok { margin-top: 30px; text-align: left; margin-left: 300px; }
        .page-break { page-break-before: always; }
        table.spd { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.spd td, table.spd th { border: 1px solid #333; padding: 5px; font-size: 11px; vertical-align: top; }
        .info-lembar { text-align: right; margin-bottom: 10px; }
        .info-lembar table { margin-left: auto; }
        .info-lembar td { padding: 1px 4px; }
    </style>
</head>
<body>

{{-- ============ HALAMAN 1: SURAT TUGAS ============ --}}
<div class="kop kop-wrap">
    <img src="{{ public_path('images/logo-kabupaten-malang.jpg') }}" class="kop-logo">
    <h2>PEMERINTAH KABUPATEN MALANG</h2>
    <h2>DINAS PENDIDIKAN</h2>
    <h1>SMP NEGERI 1 TUREN</h1>
    <p>Jalan Panglima Sudirman Nomor 1A, Kecamatan Turen, Kabupaten Malang 65175</p>
    <p>Telepon (0341) 824031; Pos-el email@smpn1turen.sch.id</p>
    <p>Laman : http://www.smpn1turen.sch.id</p>
</div>
<div class="garis-bawah"></div>

<h3 class="judul">SURAT TUGAS</h3>
<p class="nomor">Nomor : {{ $nomorSurat }}</p>

<table class="isi">
    <tr>
        <td class="label-kolom">Dasar</td>
        <td class="titik-dua">:</td>
        <td>Sebagaimana surat/undangan sebagaimana terlampir.</td>
    </tr>
</table>

<p style="text-align:center;"><strong>MEMERINTAHKAN:</strong></p>

<table class="isi">
    <tr>
        <td class="label-kolom">Kepada</td>
        <td class="titik-dua">:</td>
        <td class="label-kolom2">nama</td>
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
        <td>pangkat/golongan</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->member->pangkat ?? '-' }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>jabatan</td>
        <td class="titik-dua">:</td>
        <td>{{ $guru->member->jabatan_dinas ?? '-' }}</td>
    </tr>
    <tr>
        <td></td><td></td>
        <td>unit kerja</td>
        <td class="titik-dua">:</td>
        <td>SMP Negeri 1 Turen</td>
    </tr>
</table>

<table class="isi">
    <tr>
        <td class="label-kolom">Untuk</td>
        <td class="titik-dua">:</td>
        <td>
            Menghadiri undangan tersebut yang dilaksanakan pada hari {{ $data['hari'] ?? '-' }} pukul
            {{ $data['jam_mulai'] ?? '-' }} WIB s.d. {{ $data['jam_selesai'] ?? '(selesai)' }} bertempat di
            {{ $data['tempat_tujuan'] ?? '-' }}, dengan tema {{ $data['tema'] ?? '-' }}.
        </td>
    </tr>
</table>

<p>Sesuai prosedur setelah melaksanakan kegiatan dimaksud, agar melaporkan hasilnya kepada Pimpinan.</p>
<p>Demikian surat tugas ini untuk dilaksanakan dengan penuh tanggung jawab.</p>

<div class="ttd-blok">
    <p>Turen, {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}</p>
    <p>Kepala SMP Negeri 1 Turen</p>
    <br><br><br>
    <p style="text-decoration:underline;"><strong>{{ $pengaturan->kepsek_nama ?? '-' }}</strong></p>
    <p>{{ $pengaturan->kepsek_pangkat ?? '-' }}</p>
    <p>NIP {{ $pengaturan->kepsek_nip ?? '-' }}</p>
</div>

{{-- ============ HALAMAN 2: SPD ============ --}}
<div class="page-break"></div>

<div class="kop kop-wrap">
    <img src="{{ public_path('images/logo-kabupaten-malang.jpg') }}" class="kop-logo">
    <h2>PEMERINTAH KABUPATEN MALANG</h2>
    <h2>DINAS PENDIDIKAN</h2>
    <h1>SMP NEGERI 1 TUREN</h1>
    <p>Jalan Panglima Sudirman Nomor 1A, Kecamatan Turen, Kabupaten Malang 65175</p>
    <p>Telepon (0341) 824031; Pos-el email@smpn1turen.sch.id</p>
    <p>Laman : http://www.smpn1turen.sch.id</p>
</div>
<div class="garis-bawah"></div>

<div class="info-lembar">
    <table>
        <tr><td>Lembar ke</td><td>:</td><td>I</td></tr>
        <tr><td>Kode No.</td><td>:</td><td>.....</td></tr>
        <tr><td>Nomor</td><td>:</td><td>{{ $nomorSurat }}</td></tr>
    </table>
</div>

<h3 class="judul">SURAT PERJALANAN DINAS (SPD)</h3>
<br>

<table class="spd">
    <tr><td width="20">1.</td><td width="230">Pengguna Anggaran/Kuasa Pengguna Anggaran/Pejabat Pembuat Komitmen</td><td>: {{ $pengaturan->kepsek_nama ?? '-' }}</td></tr>
    <tr><td>2.</td><td>Nama / NIP Pegawai yang melaksanakan perjalanan dinas</td><td>: {{ $guru->nama }} / {{ $guru->nip ?? '-' }}</td></tr>
    <tr>
        <td>3.</td>
        <td>a. Pangkat dan Golongan<br>b. Jabatan/Instansi</td>
        <td>: {{ $guru->member->pangkat ?? '-' }}<br>: {{ $guru->member->jabatan_dinas ?? '-' }} / SMP Negeri 1 Turen</td>
    </tr>
    <tr><td>4.</td><td>Alat Angkut yang dipergunakan</td><td>: Perjalanan darat</td></tr>
    <tr>
        <td>5.</td>
        <td>a. Tempat Berangkat<br>b. Tempat Tujuan</td>
        <td>: SMP Negeri 1 Turen<br>: {{ $data['tempat_tujuan'] ?? '-' }}</td>
    </tr>
    <tr>
        <td>6.</td>
        <td>a. Lamanya Perjalanan Dinas<br>b. Tanggal Berangkat<br>c. Tanggal Harus Kembali/Tiba di Tempat Baru*)</td>
        <td>
            a. {{ $data['total_hari'] ?? 1 }} hari<br>
            b. {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}<br>
            c. {{ isset($data['tanggal_selesai']) && $data['tanggal_selesai'] ? \Carbon\Carbon::parse($data['tanggal_selesai'])->translatedFormat('d F Y') : '-' }}
        </td>
    </tr>
    <tr><td>7.</td><td>Pengikut</td><td>: -</td></tr>
    <tr>
        <td>8.</td>
        <td>Pembebanan Anggaran<br>a. Instansi<br>b. Akun</td>
        <td>:<br>a. BOS Sekolah<br>b. -</td>
    </tr>
    <tr><td>9.</td><td>Keterangan lain-lain</td><td>: -</td></tr>
</table>

<div class="ttd-blok">
    <p>Turen, {{ \Carbon\Carbon::parse($data['tanggal'] ?? now())->translatedFormat('d F Y') }}</p>
    <p>Kepala Sekolah</p>
    <br><br><br>
    <p style="text-decoration:underline;"><strong>{{ $pengaturan->kepsek_nama ?? '-' }}</strong></p>
    <p>NIP {{ $pengaturan->kepsek_nip ?? '-' }}</p>
</div>

</body>
</html>
