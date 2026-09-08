<style>
    @page { size: 215.9mm 330mm; margin: 10mm 5mm; }
    body { font-family: Arial, sans-serif; background-color: #f0f0f0; margin: 0; padding: 0; }
    .container { max-width: 210mm; margin: 20px auto; background: white; padding: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
    .header-nav { margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #333; padding: 10px; background: #fff; }
    .grid-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 5px; padding: 0; }
    .card { border: 1.5px solid #000; padding: 8px; width: 100mm; height: 75mm; box-sizing: border-box; position: relative; background: #fff; display: flex; flex-direction: column; justify-content: flex-start; }
    .kop-img { text-align: center; margin-bottom: 5px; padding-bottom: 4px; border-bottom: 1.5px solid #000; }
    .kop-img img { width: 100%; height: auto; display: block; }
    .title-kartu { text-align: center; font-weight: bold; font-size: 8pt; text-decoration: underline; margin: 5px 0; text-transform: uppercase; }
    .content table { width: 100%; font-size: 8.5pt; border-collapse: collapse; }
    .content td { padding: 1.5px 0; vertical-align: top; }
    .label { width: 30%; } .separator { width: 5%; } .value { width: 65%; font-weight: bold; }
    .footer-kartu { margin-top: auto; display: flex; justify-content: center; align-items: flex-end; gap: 15px; font-size: 7.5pt; padding-top: 5px; }
    .foto-box { width: 60px; height: 80px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; text-align: center; overflow: hidden; background: #f9f9f9; }
    .foto-box img { width: 100%; height: 100%; object-fit: cover; }
    .ttd { text-align: center; width: 160px; position: relative; }
    .ttd p { margin: 1px 0; }
    .img-ttd { position: absolute; width: 55px; left: 55px; top: 18px; z-index: 1; pointer-events: none; }
    .ttd-nama { margin-top: 8px; font-weight: bold; text-decoration: underline; position: relative; z-index: 2; }
    .btn-print, .btn-nav { padding: 8px 16px; color: white; border: none; cursor: pointer; border-radius: 4px; font-weight: bold; text-decoration: none; display: inline-block; font-size: 14px; }
    .btn-print { background: #27ae60; } .btn-nav { background: #3498db; }
    @media print {
        body { background: none; padding: 0; margin: 0; }
        .container { box-shadow: none; width: 100%; max-width: none; margin: 0; padding: 0; }
        .header-nav { display: none; }
        .grid-container { display: grid; grid-template-columns: repeat(2, 1fr); gap: 2mm; }
        .card { page-break-inside: avoid; margin-bottom: 2mm; }
    }
</style>
