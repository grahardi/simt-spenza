<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kebugaran Jasmani - FIT TEST</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- FontAwesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Chart.js for Radar Visualization -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .tab-btn.active {
            border-bottom: 3px solid #2563eb;
            color: #2563eb;
            font-weight: 700;
        }
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 4px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col">
<style id="landing-page-style">
  .landing-shell{font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;background:
    radial-gradient(circle at 10% 10%,rgba(16,185,129,.18),transparent 30%),
    radial-gradient(circle at 90% 15%,rgba(59,130,246,.18),transparent 28%),
    linear-gradient(135deg,#f8fafc 0%,#ecfdf5 48%,#eff6ff 100%);min-height:100vh}
  .landing{max-width:1180px;margin:0 auto;padding:28px 22px 70px}
  .landing-nav{display:flex;align-items:center;justify-content:space-between;gap:18px;padding:8px 0 42px}
  .brand{display:flex;align-items:center;gap:12px;font-weight:900;color:#0f172a}
  .brand-icon{width:44px;height:44px;border-radius:14px;display:grid;place-items:center;background:linear-gradient(135deg,#059669,#2563eb);color:#fff;box-shadow:0 12px 28px rgba(37,99,235,.22)}
  .school{font-size:12px;color:#64748b;font-weight:700}
  .hero{display:grid;grid-template-columns:1.15fr .85fr;gap:42px;align-items:center;padding:28px 0 54px}
  .eyebrow{display:inline-flex;align-items:center;gap:8px;padding:8px 13px;border-radius:999px;background:#fff;border:1px solid #dbeafe;color:#2563eb;font-size:12px;font-weight:900;letter-spacing:.04em}
  .hero h1{font-size:clamp(38px,6vw,70px);line-height:.98;margin:18px 0 18px;font-weight:950;letter-spacing:-.045em;color:#0f172a}
  .hero h1 span{background:linear-gradient(90deg,#059669,#2563eb);-webkit-background-clip:text;background-clip:text;color:transparent}
  .hero p{font-size:17px;line-height:1.75;color:#475569;max-width:650px;margin:0}
  .hero-actions{display:flex;flex-wrap:wrap;gap:12px;margin-top:28px}
  .cta{display:inline-flex;align-items:center;justify-content:center;gap:10px;padding:14px 20px;border-radius:14px;font-weight:900;text-decoration:none;border:0;cursor:pointer}
  .cta-primary{color:#fff;background:linear-gradient(135deg,#059669,#2563eb);box-shadow:0 14px 30px rgba(5,150,105,.24)}
  .cta-secondary{color:#0f172a;background:#fff;border:1px solid #cbd5e1}
  .hero-card{position:relative;border-radius:28px;padding:25px;background:rgba(255,255,255,.78);backdrop-filter:blur(16px);border:1px solid rgba(255,255,255,.9);box-shadow:0 25px 70px rgba(15,23,42,.12)}
  .score-ring{width:170px;height:170px;border-radius:50%;margin:8px auto 20px;display:grid;place-items:center;background:conic-gradient(#10b981 0 82%,#dbeafe 82% 100%);position:relative}
  .score-ring:after{content:"";position:absolute;inset:13px;border-radius:50%;background:#fff}
  .score-number{position:relative;z-index:1;text-align:center}.score-number strong{display:block;font-size:38px;color:#0f172a}.score-number small{color:#64748b;font-weight:700}
  .mini-grid{display:grid;grid-template-columns:1fr 1fr;gap:10px}
  .mini{padding:14px;border-radius:16px;background:#f8fafc;border:1px solid #e2e8f0}.mini b{display:block;color:#0f172a}.mini span{font-size:12px;color:#64748b}
  .section{padding:28px 0}.section-title{text-align:center;margin-bottom:25px}.section-title h2{font-size:32px;margin:0;color:#0f172a}.section-title p{color:#64748b}
  .features{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}
  .feature{background:#fff;border:1px solid #e2e8f0;border-radius:20px;padding:21px;box-shadow:0 8px 25px rgba(15,23,42,.05)}
  .feature-icon{width:44px;height:44px;border-radius:13px;display:grid;place-items:center;background:#ecfdf5;color:#059669;margin-bottom:15px}.feature h3{font-size:16px;margin:0 0 7px}.feature p{font-size:13px;line-height:1.65;color:#64748b;margin:0}
  .components{display:grid;grid-template-columns:repeat(4,1fr);gap:14px}.component{border-radius:18px;padding:18px;color:#fff;min-height:120px;display:flex;flex-direction:column;justify-content:space-between;box-shadow:0 12px 30px rgba(15,23,42,.12)}.component:nth-child(1){background:#0f766e}.component:nth-child(2){background:#2563eb}.component:nth-child(3){background:#7c3aed}.component:nth-child(4){background:#ea580c}.component b{font-size:18px}.component span{font-size:12px;opacity:.9}
  .steps{display:grid;grid-template-columns:repeat(4,1fr);gap:15px}.step{background:#fff;border:1px solid #e2e8f0;border-radius:18px;padding:20px}.step-num{font-size:12px;font-weight:950;color:#2563eb}.step h3{margin:8px 0 7px;font-size:16px}.step p{font-size:13px;color:#64748b;line-height:1.6;margin:0}
  .landing-footer{text-align:center;padding-top:45px;color:#64748b;font-size:12px}
  .launch-bar{position:sticky;bottom:18px;z-index:50;display:flex;justify-content:center;pointer-events:none}.launch-bar a{pointer-events:auto}
  @media(max-width:900px){.hero{grid-template-columns:1fr}.features,.components,.steps{grid-template-columns:repeat(2,1fr)}}
  @media(max-width:560px){.landing{padding-left:16px;padding-right:16px}.landing-nav{padding-bottom:25px}.school{display:none}.features,.components,.steps{grid-template-columns:1fr}.hero h1{font-size:43px}.hero-actions .cta{width:100%}}
</style>

<div id="landing-page" class="landing-shell">
  <div class="landing">
    <nav class="landing-nav">
      <div class="brand">
        <div class="brand-icon"><i class="fas fa-heartbeat"></i></div>
        <div>FIT TEST<div class="school">SMPN 1 TUREN</div></div>
      </div>
      <a class="cta cta-secondary" href="{{ route('fit7-jasmani.aplikasi') }}"><i class="fas fa-arrow-down"></i> Lihat Aplikasi</a>
    </nav>

    <section class="hero">
      <div>
        <div class="eyebrow"><i class="fas fa-bolt"></i> ASESMEN PJOK SMP</div>
        <h1>Ukur Kebugaran.<br><span>Pahami Perkembangan.</span></h1>
        <p>
          Aplikasi digital untuk membantu peserta didik dan guru melakukan asesmen
          kebugaran jasmani secara praktis, terstruktur, dan mudah dipantau.
          Dilengkapi kalkulator individu, rekap kelas, stopwatch, serta materi dan standar.
        </p>
        <div class="hero-actions">
          <a class="cta cta-primary" href="{{ route('fit7-jasmani.aplikasi') }}"><i class="fas fa-play"></i> Mulai Aplikasi</a>
          <a class="cta cta-secondary" href="#fitur"><i class="fas fa-th-large"></i> Lihat Fitur</a>
        </div>
      </div>

      <div class="hero-card">
        <div class="score-ring">
          <div class="score-number"><strong>17–20</strong><small>Baik Sekali</small></div>
        </div>
        <div class="mini-grid">
          <div class="mini"><b>50 m</b><span>Kecepatan</span></div>
          <div class="mini"><b>4×10 m</b><span>Kelincahan</span></div>
          <div class="mini"><b>60 detik</b><span>Kekuatan</span></div>
          <div class="mini"><b>800/1000 m</b><span>Daya tahan</span></div>
        </div>
      </div>
    </section>

    <section id="fitur" class="section">
      <div class="section-title">
        <h2>Semua yang Dibutuhkan</h2>
        <p>Satu halaman untuk mendukung proses asesmen kebugaran jasmani.</p>
      </div>
      <div class="features">
        <div class="feature"><div class="feature-icon"><i class="fas fa-calculator"></i></div><h3>Kalkulator Individu</h3><p>Masukkan hasil tes dan lihat skor setiap komponen serta total kebugaran.</p></div>
        <div class="feature"><div class="feature-icon"><i class="fas fa-users"></i></div><h3>Rekap Data Kelas</h3><p>Simpan hasil asesmen di perangkat dan kelola data peserta didik dengan mudah.</p></div>
        <div class="feature"><div class="feature-icon"><i class="fas fa-stopwatch"></i></div><h3>Stopwatch Lapangan</h3><p>Gunakan stopwatch dan timer 60 detik untuk membantu pelaksanaan tes.</p></div>
        <div class="feature"><div class="feature-icon"><i class="fas fa-book-open"></i></div><h3>Materi & Standar</h3><p>Akses materi kebugaran dan tabel standar normatif yang tersedia di aplikasi.</p></div>
      </div>
    </section>

    <section class="section">
      <div class="section-title"><h2>Komponen Kebugaran</h2><p>Empat komponen utama yang tersedia pada asesmen.</p></div>
      <div class="components">
        <div class="component"><b><i class="fas fa-running"></i> Kecepatan</b><span>Sprint 50 meter</span></div>
        <div class="component"><b><i class="fas fa-random"></i> Kelincahan</b><span>Shuttle run 4×10 meter</span></div>
        <div class="component"><b><i class="fas fa-dumbbell"></i> Kekuatan</b><span>Sit-up 60 detik</span></div>
        <div class="component"><b><i class="fas fa-heart"></i> Cardio</b><span>1000 m putra / 800 m putri</span></div>
      </div>
    </section>

    <section class="section">
      <div class="section-title"><h2>Cara Menggunakan</h2><p>Alur sederhana untuk melakukan asesmen.</p></div>
      <div class="steps">
        <div class="step"><div class="step-num">01 — DATA</div><h3>Isi Identitas</h3><p>Masukkan nama, kelas, usia, dan jenis kelamin peserta didik.</p></div>
        <div class="step"><div class="step-num">02 — TES</div><h3>Masukkan Hasil</h3><p>Input hasil sprint, shuttle run, sit-up, dan tes cardio.</p></div>
        <div class="step"><div class="step-num">03 — ANALISIS</div><h3>Lihat Skor</h3><p>Aplikasi menghitung skor komponen, total, kategori, dan radar.</p></div>
        <div class="step"><div class="step-num">04 — REKAP</div><h3>Simpan & Pantau</h3><p>Simpan hasil dan gunakan rekap kelas untuk memantau perkembangan.</p></div>
      </div>
    </section>

    <div class="launch-bar">
      <a class="cta cta-primary" href="{{ route('fit7-jasmani.aplikasi') }}"><i class="fas fa-rocket"></i> Mulai Asesmen Sekarang</a>
    </div>
    <footer class="landing-footer">
      FIT TEST • Aplikasi Evaluasi Kebugaran Jasmani SMP • SMPN 1 Turen
    </footer>
  </div>
</div>
</body>
</html>
