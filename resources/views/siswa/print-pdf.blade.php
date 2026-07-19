<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 12px; color: #222; }
        .kop { text-align: center; margin-bottom: 4px; }
        hr { border: none; border-top: 2px solid #333; margin: 8px 0 16px; }
        h3 { text-align: center; margin: 0 0 16px; font-size: 14px; text-decoration: underline; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #444; padding: 5px 8px; font-size: 11px; }
        th { background: #eee; text-align: left; }
        .center { text-align: center; }
        .footer { margin-top: 40px; text-align: right; font-size: 11px; }
    </style>
</head>
<body>
    <div class="kop">
        <img src="{{ storage_path('app/public/gambar/header.png') }}" style="width:100%;max-height:120px;object-fit:contain;">
    </div>
    <hr>
    <h3>DAFTAR NAMA SISWA KELAS {{ $kelas }}</h3>

    <table>
        <thead>
            <tr>
                <th class="center" style="width:30px;">No</th>
                <th style="width:90px;">No. Induk</th>
                <th style="width:100px;">NISN</th>
                <th>Nama Lengkap</th>
                <th class="center" style="width:50px;">L/P</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($siswa as $i => $s)
                <tr>
                    <td class="center">{{ $i + 1 }}</td>
                    <td>{{ $s->id_member }}</td>
                    <td>{{ $s->nisn }}</td>
                    <td>{{ $s->nama_lengkap }}</td>
                    <td class="center">{{ $s->jenis_kelamin }}</td>
                </tr>
            @empty
                <tr><td colspan="5" class="center">Belum ada siswa di kelas ini.</td></tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        Turen, {{ now()->translatedFormat('d F Y') }}
    </div>
</body>
</html>
