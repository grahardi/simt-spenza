<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Label Meja - SIMT</title>
    @include('kartu-ujian._style')
</head>
<body>
<div class="container">
    <div class="header-nav">
        <h2 class="m-0">Label Meja Peserta (F4 - Tanpa Password)</h2>
        <button class="btn-print" onclick="window.print()">Cetak</button>
    </div>
    <div class="grid-container">
        @forelse ($peserta as $p)
            @include('kartu-ujian._kartu', ['p' => $p, 'tampilkanPassword' => false])
        @empty
            <p style="padding:20px; grid-column: span 2;">Belum ada data.</p>
        @endforelse
    </div>
</div>
</body>
</html>
