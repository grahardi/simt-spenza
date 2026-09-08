<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Semua Denah - SIMT</title>
    <style>
        @page { size: 215.9mm 330mm; margin: 10mm; }
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 20px; }
        .no-print { background: #fff; padding: 15px; text-align: center; margin-bottom: 20px; }
        .page-container { width: 210mm; background: white; margin: 0 auto 20px auto; padding: 10px; min-height: 297mm; page-break-after: always; }
        .header { text-align: center; border-bottom: 2px solid #000; margin-bottom: 20px; padding-bottom: 10px; }
        .info-ruang { display: flex; justify-content: space-between; margin-bottom: 15px; font-weight: bold; text-transform: uppercase; }
        .grid-denah { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; justify-items: center; }
        .meja { width: 45mm; height: 55mm; border: 2px solid #333; display: flex; flex-direction: column; align-items: center; padding: 5px; box-sizing: border-box; background: #fff; position: relative; }
        .meja .foto { width: 25mm; height: 30mm; border: 1px solid #ccc; margin-bottom: 5px; overflow: hidden; display: flex; align-items: center; justify-content: center; background: #eee; }
        .meja .foto img { width: 100%; height: 100%; object-fit: cover; }
        .meja .user-id { font-size: 9pt; font-weight: bold; color: #d35400; }
        .meja .nama { font-size: 8pt; text-align: center; font-weight: bold; margin-top: 2px; line-height: 1.1; }
        .meja .kelas { font-size: 7pt; margin-top: auto; }
        .label-pintu { grid-column: span 4; text-align: center; background: #000; color: #fff; padding: 5px; font-weight: bold; margin-bottom: 20px; }
        .empty-seat { width: 45mm; height: 55mm; border: 1px dashed #ccc; }
        @media print {
            body { background: none; padding: 0; }
            .no-print { display: none; }
            .page-container { margin: 0; border: none; box-shadow: none; width: 100%; }
        }
    </style>
</head>
<body>
<div class="no-print">
    <h2>Cetak Massal Denah Tempat Duduk</h2>
    <p>Total Ruang: <strong>{{ $semuaRuang->count() }}</strong></p>
    <button onclick="window.print()" style="padding:10px 20px; background:#27ae60; color:#fff; border:none; cursor:pointer; border-radius:4px;">CETAK SEMUA</button>
</div>

@foreach ($semuaRuang as $r)
    <div class="page-container">
        @include('kartu-ujian._denah-satu', ['ruang' => $r->ruang, 'tipeDenah' => $r->tipeDenah, 'peserta' => $r->peserta, 'gridDenah' => $r->gridDenah])
    </div>
@endforeach
</body>
</html>
