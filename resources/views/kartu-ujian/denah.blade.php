<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Denah Ruang {{ $ruang }} - SIMT</title>
    <style>
        @page { size: 215.9mm 330mm; margin: 10mm; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .page-container { width: 210mm; background: white; margin: 0 auto; padding: 10px; min-height: 297mm; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .info-ruang { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: bold; text-transform: uppercase; }
        .grid-denah { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; justify-items: center; }
        .meja { width: 45mm; border: 2px solid #333; box-sizing: border-box; background: #fff; }
        .label-meja { text-align: center; font-weight: bold; font-size: 8pt; background: #eee; border-bottom: 1px solid #333; padding: 2px 0; }
        .meja-isi { display: flex; }
        .kartu-mini { flex: 1; display: flex; flex-direction: column; align-items: center; padding: 4px 2px; border-right: 1px dashed #999; }
        .kartu-mini:last-child { border-right: none; }
        .kartu-mini .foto { width: 18mm; height: 24mm; border: 1px solid #ccc; background: #eee; margin-bottom: 3px; overflow: hidden; display: flex; align-items: center; justify-content: center; }
        .kartu-mini .foto img { width: 100%; height: 100%; object-fit: cover; }
        .kartu-mini .user-id { font-size: 7.5pt; font-weight: bold; color: #d35400; }
        .kartu-mini .nama { font-size: 6.5pt; text-align: center; font-weight: bold; margin-top: 1px; line-height: 1.05; }
        .label-pintu { grid-column: span 4; text-align: center; background: #000; color: #fff; padding: 5px; font-weight: bold; margin-bottom: 20px; }
        .empty-seat { width: 45mm; height: 40mm; border: 1px dashed #ccc; }
        @media print {
            body { background: none; padding: 0; }
            .no-print { display: none; }
            .page-container { margin: 0; border: none; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>
<div class="no-print" style="margin-bottom:20px; text-align:center;">
    <button onclick="window.print()">Cetak Denah</button>
</div>
<div class="page-container">
    @include('kartu-ujian._denah-satu')
</div>
</body>
</html>
