<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Ujian - SIMT</title>
    @include('kartu-ujian._style')
</head>
<body>
<div class="container">
    <div class="header-nav">
        <h2 class="m-0">Kartu Ujian Peserta (F4 - 8 Kartu/Lembar)</h2>
        <div>
            <a href="{{ route('kartu-ujian.import') }}" class="btn-nav">Import Data</a>
            <button class="btn-print" onclick="window.print()">Cetak</button>
        </div>
    </div>
    <div class="grid-container">
        @forelse ($peserta as $p)
            @include('kartu-ujian._kartu', ['p' => $p, 'tampilkanPassword' => true])
        @empty
            <p style="padding:20px; grid-column: span 2;">
                Belum ada data. Silakan <a href="{{ route('kartu-ujian.import') }}">import data</a> terlebih dahulu.
            </p>
        @endforelse
    </div>
</div>
</body>
</html>
