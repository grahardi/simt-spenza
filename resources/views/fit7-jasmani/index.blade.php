<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aplikasi Kebugaran Jasmani Kelas 7 SMP</title>
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
        <div>FIT7 JASMANI<div class="school">SMPN 1 TUREN</div></div>
      </div>
      <a class="cta cta-secondary" href="#app"><i class="fas fa-arrow-down"></i> Lihat Aplikasi</a>
    </nav>

    <section class="hero">
      <div>
        <div class="eyebrow"><i class="fas fa-bolt"></i> ASESMEN PJOK KELAS 7</div>
        <h1>Ukur Kebugaran.<br><span>Pahami Perkembangan.</span></h1>
        <p>
          Aplikasi digital untuk membantu peserta didik dan guru melakukan asesmen
          kebugaran jasmani kelas 7 secara praktis, terstruktur, dan mudah dipantau.
          Dilengkapi kalkulator individu, rekap kelas, stopwatch, serta materi dan standar.
        </p>
        <div class="hero-actions">
          <a class="cta cta-primary" href="#app"><i class="fas fa-play"></i> Mulai Aplikasi</a>
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
      <a class="cta cta-primary" href="#app"><i class="fas fa-rocket"></i> Mulai Asesmen Sekarang</a>
    </div>
    <footer class="landing-footer">
      FIT7 JASMANI • Aplikasi Evaluasi Kebugaran Jasmani Kelas 7 SMP • SMPN 1 Turen
    </footer>
  </div>
</div>

<style id="app-wrapper-style">
  html{scroll-behavior:smooth}
  #app{scroll-margin-top:20px}
  .app-back{display:none;max-width:1200px;margin:0 auto;padding:16px 22px}
  .app-back a{display:inline-flex;align-items:center;gap:8px;padding:9px 13px;border-radius:10px;background:#fff;border:1px solid #e2e8f0;color:#334155;text-decoration:none;font-weight:800;font-size:13px}
  body.app-mode #landing-page{display:none}
  body.app-mode .app-back{display:block}
</style>

<div id="app">


    <!-- Header App -->
    <header class="bg-gradient-to-r from-blue-700 via-indigo-700 to-blue-900 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-3">
                    <div class="bg-white/10 p-2 rounded-xl backdrop-blur-md">
                        <i class="fa-solid me-1 fa-child-reaching text-2xl text-yellow-300"></i>
                    </div>
                    <div>
                        <h1 class="text-xl font-extrabold tracking-tight">FIT7 <span class="text-yellow-300">JASMANI</span></h1>
                        <p class="text-xs text-blue-200">Asesmen & Modul Kebugaran Jasmani Kelas 7 SMP (TKJI 13-15 Thn)</p>
                    </div>
                </div>
                <div class="hidden md:flex items-center space-x-2 text-xs bg-blue-800/60 px-3 py-1.5 rounded-full border border-blue-400/30">
                    <i class="fa-solid fa-graduation-cap text-yellow-300"></i>
                    <span>Kurikulum Merdeka / PJOK SMP</span>
                </div>
            </div>
        </div>
        
        <!-- Tab Navigation Bar -->
        <div class="bg-white text-slate-600 border-b border-slate-200 shadow-sm overflow-x-auto">
            <div class="max-w-7xl mx-auto px-4 flex space-x-6 sm:space-x-8 text-sm">
                <button onclick="switchTab('kalkulator')" id="tab-kalkulator" class="tab-btn active py-3.5 px-1 font-semibold flex items-center space-x-2 whitespace-nowrap transition-colors">
                    <i class="fa-solid fa-calculator text-blue-600"></i>
                    <span>Uji & Kalkulator Individu</span>
                </button>
                <button onclick="switchTab('rekap')" id="tab-rekap" class="tab-btn py-3.5 px-1 font-medium flex items-center space-x-2 whitespace-nowrap transition-colors">
                    <i class="fa-solid fa-users text-indigo-600"></i>
                    <span>Rekap Data Kelas</span>
                </button>
                <button onclick="switchTab('timer')" id="tab-timer" class="tab-btn py-3.5 px-1 font-medium flex items-center space-x-2 whitespace-nowrap transition-colors">
                    <i class="fa-solid fa-stopwatch text-emerald-600"></i>
                    <span>Stopwatch Lapangan</span>
                </button>
                <button onclick="switchTab('materi')" id="tab-materi" class="tab-btn py-3.5 px-1 font-medium flex items-center space-x-2 whitespace-nowrap transition-colors">
                    <i class="fa-solid fa-book-open text-amber-600"></i>
                    <span>Materi & Standar Normatix</span>
                </button>
            </div>
        </div>
    </header>

    <main class="flex-grow max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- TAB 1: KALKULATOR & PENILAIAN INDIVIDU -->
        <section id="sec-kalkulator" class="space-y-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6 border-b border-slate-100 pb-4">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-user-check text-blue-600"></i>
                            Input Data & Tes Kebugaran Siswa
                        </h2>
                        <p class="text-sm text-slate-500">Masukkan data hasil tes 4 aspek kebugaran jasmani untuk penilaian otomatis.</p>
                    </div>
                    <div class="flex items-center space-x-2 bg-slate-100 p-1 rounded-xl">
                        <button type="button" onclick="setGender('L')" id="btn-gender-L" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm bg-blue-600 text-white">
                            <i class="fa-solid fa-mars me-1"></i> Laki-Laki (Putra)
                        </button>
                        <button type="button" onclick="setGender('P')" id="btn-gender-P" class="px-4 py-2 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:bg-white">
                            <i class="fa-solid fa-venus me-1"></i> Perempuan (Putri)
                        </button>
                    </div>
                </div>

                <form id="fitnessForm" onsubmit="calculateAndSave(event)" class="space-y-6">
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 bg-slate-50 p-4 rounded-xl border border-slate-200">
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Kelas</label>
                            <select id="inputKelas" onchange="muatSiswaKelas()" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm bg-white">
                                <option value="">- Pilih kelas -</option>
                                @foreach (['A','B','C','D','E','F','G','H','I','J'] as $huruf)
                                    <option value="7 - {{ $huruf }}">7 - {{ $huruf }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Nama Lengkap Siswa</label>
                            <select id="inputNama" required onchange="isiUsiaOtomatis()" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm bg-white">
                                <option value="">- Pilih kelas dulu -</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-600 uppercase mb-1">Usia Siswa</label>
                            <select id="inputUsia" class="w-full px-3 py-2 border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none text-sm bg-white">
                                <option value="13">13 Tahun (Kelas 7 SMP)</option>
                                <option value="14">14 Tahun (Kelas 7/8 SMP)</option>
                                <option value="15">15 Tahun (Kelas 7/8/9 SMP)</option>
                            </select>
                        </div>
                    </div>

                    <!-- GRID 4 ASPEK KEBUGARAN -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
                        
                        <!-- 1. KECEPATAN -->
                        <div class="bg-blue-50/60 rounded-xl p-4 border border-blue-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-blue-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">1. Kecepatan</span>
                                    <i class="fa-solid fa-bolt text-blue-600 text-lg"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Lari Sprint 50 Meter</h3>
                                <p class="text-xs text-slate-500 mb-3">Satuan dalam <strong class="text-slate-700">Detik</strong> (Presisi 0.01 detik).</p>
                            </div>
                            <div>
                                <div class="relative">
                                    <input type="number" step="0.01" min="3" max="30" id="valKecepatan" required placeholder="Contoh: 8.2" oninput="liveUpdateCalc()" class="w-full pl-3 pr-12 py-2 border border-blue-300 rounded-lg focus:ring-2 focus:ring-blue-500 outline-none font-semibold text-slate-800">
                                    <span class="absolute right-3 top-2 text-xs font-bold text-slate-400">Detik</span>
                                </div>
                                <div id="normaKecepatan" class="mt-2 text-xs font-semibold text-blue-700 bg-white p-2 rounded border border-blue-100 flex justify-between items-center">
                                    <span>Skor Nilai:</span> <span id="scoreKecepatan" class="font-bold text-sm">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- 2. KELINCAHAN -->
                        <div class="bg-indigo-50/60 rounded-xl p-4 border border-indigo-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-indigo-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">2. Kelincahan</span>
                                    <i class="fa-solid fa-arrows-split-up-and-left text-indigo-600 text-lg"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Shuttle Run (4 x 10 Meter)</h3>
                                <p class="text-xs text-slate-500 mb-3">Satuan dalam <strong class="text-slate-700">Detik</strong>.</p>
                            </div>
                            <div>
                                <div class="relative">
                                    <input type="number" step="0.01" min="5" max="40" id="valKelincahan" required placeholder="Contoh: 11.5" oninput="liveUpdateCalc()" class="w-full pl-3 pr-12 py-2 border border-indigo-300 rounded-lg focus:ring-2 focus:ring-indigo-500 outline-none font-semibold text-slate-800">
                                    <span class="absolute right-3 top-2 text-xs font-bold text-slate-400">Detik</span>
                                </div>
                                <div id="normaKelincahan" class="mt-2 text-xs font-semibold text-indigo-700 bg-white p-2 rounded border border-indigo-100 flex justify-between items-center">
                                    <span>Skor Nilai:</span> <span id="scoreKelincahan" class="font-bold text-sm">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- 3. KEKUATAN -->
                        <div class="bg-emerald-50/60 rounded-xl p-4 border border-emerald-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-emerald-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">3. Kekuatan</span>
                                    <i class="fa-solid fa-dumbbell text-emerald-600 text-lg"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1">Sit-Up (Baring Duduk 60d)</h3>
                                <p class="text-xs text-slate-500 mb-3">Jumlah pengulangan sempurna dalam <strong class="text-slate-700">60 Detik</strong>.</p>
                            </div>
                            <div>
                                <div class="relative">
                                    <input type="number" step="1" min="0" max="100" id="valKekuatan" required placeholder="Contoh: 25" oninput="liveUpdateCalc()" class="w-full pl-3 pr-12 py-2 border border-emerald-300 rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none font-semibold text-slate-800">
                                    <span class="absolute right-3 top-2 text-xs font-bold text-slate-400">Kali</span>
                                </div>
                                <div id="normaKekuatan" class="mt-2 text-xs font-semibold text-emerald-700 bg-white p-2 rounded border border-emerald-100 flex justify-between items-center">
                                    <span>Skor Nilai:</span> <span id="scoreKekuatan" class="font-bold text-sm">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- 4. JANTUNG & PARU-PARU -->
                        <div class="bg-amber-50/60 rounded-xl p-4 border border-amber-200 flex flex-col justify-between">
                            <div>
                                <div class="flex items-center justify-between mb-2">
                                    <span class="bg-amber-600 text-white text-xs px-2.5 py-1 rounded-full font-bold">4. Cardio (Jantung-Paru)</span>
                                    <i class="fa-solid fa-heart-pulse text-amber-600 text-lg"></i>
                                </div>
                                <h3 class="font-bold text-slate-800 text-sm mb-1" id="labelDayaTahan">Lari 1000m (Putra) / 800m (Putri)</h3>
                                <p class="text-xs text-slate-500 mb-3">Waktu tempuh dalam <strong class="text-slate-700">Menit & Detik</strong>.</p>
                            </div>
                            <div>
                                <div class="grid grid-cols-2 gap-2">
                                    <div class="relative">
                                        <input type="number" step="1" min="1" max="30" id="valCardioMin" required placeholder="Menit" oninput="liveUpdateCalc()" class="w-full px-2 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none font-semibold text-slate-800 text-sm">
                                        <span class="absolute right-2 top-2 text.xs font-bold text-slate-400">m</span>
                                    </div>
                                    <div class="relative">
                                        <input type="number" step="1" min="0" max="59" id="valCardioSec" required placeholder="Detik" oninput="liveUpdateCalc()" class="w-full px-2 py-2 border border-amber-300 rounded-lg focus:ring-2 focus:ring-amber-500 outline-none font-semibold text-slate-800 text-sm">
                                        <span class="absolute right-2 top-2 text-xs font-bold text-slate-400">s</span>
                                    </div>
                                </div>
                                <div id="normaCardio" class="mt-2 text-xs font-semibold text-amber-700 bg-white p-2 rounded border border-amber-100 flex justify-between items-center">
                                    <span>Skor Nilai:</span> <span id="scoreCardio" class="font-bold text-sm">-</span>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- LIVE RESULT SUMMARY BOX -->
                    <div class="bg-slate-900 text-white rounded-2xl p-6 shadow-md grid grid-cols-1 md:grid-cols-3 gap-6 items-center">
                        <div class="space-y-2">
                            <span class="text-xs uppercase tracking-wider text-slate-400 font-bold">Total Skor & Klasifikasi</span>
                            <div class="flex items-baseline space-x-3">
                                <span id="displayTotalSkor" class="text-4xl font-extrabold text-yellow-400">0</span>
                                <span class="text-slate-400 text-sm">/ 20 Poin Maksimal</span>
                            </div>
                            <div id="badgeKategori" class="inline-block bg-slate-800 text-slate-300 px-3 py-1.5 rounded-lg font-bold text-sm border border-slate-700">
                                Belum Lengkap
                            </div>
                        </div>

                        <!-- Mini breakdown -->
                        <div class="text-xs space-y-1.5 border-y md:border-y-0 md:border-x border-slate-700 py-3 md:py-0 md:px-6">
                            <p class="text-slate-400 font-semibold mb-1">Rincian Skor Per Aspek:</p>
                            <div class="flex justify-between"><span class="text-slate-300">Kecepatan (50m):</span> <span id="breakKecepatan" class="font-bold text-yellow-300">-</span></div>
                            <div class="flex justify-between"><span class="text-slate-300">Kelincahan (Shuttle):</span> <span id="breakKelincahan" class="font-bold text-yellow-300">-</span></div>
                            <div class="flex justify-between"><span class="text-slate-300">Kekuatan (Sit-up):</span> <span id="breakKekuatan" class="font-bold text-yellow-300">-</span></div>
                            <div class="flex justify-between"><span class="text-slate-300">Daya Tahan Paru-Jantung:</span> <span id="breakCardio" class="font-bold text-yellow-300">-</span></div>
                        </div>

                        <div class="flex flex-col gap-2">
                            <button type="submit" class="w-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-3 px-4 rounded-xl shadow-lg transition duration-200 flex items-center justify-center space-x-2">
                                <i class="fa-solid fa-save"></i>
                                <span>Simpan Data Ke Rekap Kelas</span>
                            </button>
                            <button type="button" onclick="resetForm()" class="w-full bg-slate-800 hover:bg-slate-700 text-slate-300 font-medium py-2 px-4 rounded-xl transition text-xs flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-rotate-left"></i>
                                <span>Reset Form Input</span>
                            </button>
                        </div>
                    </div>
                </form>

                <!-- RADAR CHART PRESENTATION -->
                <div class="mt-8 pt-6 border-t border-slate-200 grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                    <div>
                        <h3 class="text-base font-bold text-slate-800 mb-2 flex items-center gap-2">
                            <i class="fa-solid fa-chart-pie text-indigo-600"></i>
                            Visualisasi Profil Kebugaran Siswa
                        </h3>
                        <p class="text-xs text-slate-500 mb-4">Grafik radar menunjukkan keunggulan dan aspek kebugaran jasmani yang perlu ditingkatkan oleh siswa (Skala Nilai 1 - 5).</p>
                        <div id="saranPengembangan" class="bg-blue-50 border border-blue-200 p-4 rounded-xl text-xs text-blue-900 leading-relaxed">
                            <i class="fa-solid fa-lightbulb text-amber-500 me-1"></i>
                            <strong>Petunjuk:</strong> Masukkan data hasil tes di atas untuk melihat grafik profil kebugaran dan rekomendasi latihan fisik secara realtime.
                        </div>
                    </div>
                    <div class="bg-slate-50 p-4 rounded-2xl border border-slate-200 flex justify-center items-center h-64">
                        <canvas id="radarChart"></canvas>
                    </div>
                </div>
            </div>
        </section>

        <!-- TAB 2: REKAP DATA KELAS -->
        <section id="sec-rekap" class="hidden space-y-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                            <i class="fa-solid fa-table-list text-indigo-600"></i>
                            Rekapitulasi Hasil Tes Kebugaran Kelas 7
                        </h2>
                        <p class="text-sm text-slate-500">Daftar penilaian seluruh siswa beserta kategori kebugaran jasmani.</p>
                    </div>
                    <div class="flex flex-wrap items-center gap-2">
                        <button onclick="exportToCSV()" class="bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold px-3.5 py-2 rounded-lg transition flex items-center gap-1.5 shadow-sm">
                            <i class="fa-solid fa-file-excel"></i> Export CSV
                        </button>
                        <button onclick="clearAllData()" class="bg-rose-100 hover:bg-rose-200 text-rose-700 text-xs font-bold px-3.5 py-2 rounded-lg transition flex items-center gap-1.5">
                            <i class="fa-solid fa-trash-can"></i> Hapus Semua
                        </button>
                    </div>
                </div>

                <!-- Filter & Search -->
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                    <div>
                        <input type="text" id="searchSiswa" onkeyup="renderTable()" placeholder="Cari nama siswa..." class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500">
                    </div>
                    <div>
                        <select id="filterGender" onchange="renderTable()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="ALL">Semua Jenis Kelamin</option>
                            <option value="L">Hanya Laki-Laki (Putra)</option>
                            <option value="P">Hanya Perempuan (Putri)</option>
                        </select>
                    </div>
                    <div>
                        <select id="filterKategori" onchange="renderTable()" class="w-full px-3 py-2 border border-slate-300 rounded-lg text-xs outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                            <option value="ALL">Semua Kategori Kebugaran</option>
                            <option value="Baik Sekali">Baik Sekali</option>
                            <option value="Baik">Baik</option>
                            <option value="Sedang">Sedang</option>
                            <option value="Kurang">Kurang</option>
                            <option value="Kurang Sekali">Kurang Sekali</option>
                        </select>
                    </div>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto rounded-xl border border-slate-200">
                    <table class="w-full text-xs text-left text-slate-600">
                        <thead class="bg-slate-100 text-slate-700 uppercase font-bold text-[11px] tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="p-3">#</th>
                                <th class="p-3">Siswa</th>
                                <th class="p-3">JK</th>
                                <th class="p-3 text-center">Kecepatan (50m)</th>
                                <th class="p-3 text-center">Kelincahan (Shuttle)</th>
                                <th class="p-3 text-center">Kekuatan (Sit-Up)</th>
                                <th class="p-3 text-center">Cardio (Lari)</th>
                                <th class="p-3 text-center">Total Poin</th>
                                <th class="p-3 text-center">Kategori</th>
                                <th class="p-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="tableBodyData" class="divide-y divide-slate-200 bg-white">
                            <!-- JS Inject Table Rows -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyTableNotice" class="hidden text-center py-12 text-slate-400 text-sm">
                    <i class="fa-regular fa-folder-open text-4xl mb-2 block"></i>
                    Belum ada data hasil tes yang tersimpan. Silakan isi pada menu Kalkulator Individu.
                </div>
            </div>
        </section>

        <!-- TAB 3: STOPWATCH FIELD TOOL -->
        <section id="sec-timer" class="hidden space-y-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200">
                <div class="mb-6">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-stopwatch text-emerald-600"></i>
                        Stopwatch & Timer Lapangan PJOK
                    </h2>
                    <p class="text-sm text-slate-500">Gunakan timer digital ini untuk mencatat waktu tes lari sprint 50m, shuttle run, maupun hitung mundur sit-up 60 detik secara langsung di lapangan.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    
                    <!-- STOPWATCH LARI -->
                    <div class="bg-slate-900 text-white rounded-2xl p-6 flex flex-col items-center justify-between shadow-lg">
                        <div class="w-full flex justify-between items-center text-xs text-slate-400 font-semibold mb-2">
                            <span>STOPWATCH LARI & SHUTTLE RUN</span>
                            <i class="fa-solid fa-person-running text-emerald-400"></i>
                        </div>
                        <div class="my-6 text-center">
                            <span id="swDisplay" class="font-mono text-5xl sm:text-6xl font-extrabold tracking-wider text-emerald-400">00:00.00</span>
                        </div>
                        <div class="flex space-x-3 w-full">
                            <button id="swStartBtn" onclick="toggleStopwatch()" class="flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-play"></i> <span id="swStartText">Mulai</span>
                            </button>
                            <button onclick="lapStopwatch()" class="bg-slate-800 hover:bg-slate-700 text-slate-200 font-bold px-4 py-2.5 rounded-xl transition text-sm">
                                Lap
                            </button>
                            <button onclick="resetStopwatch()" class="bg-slate-800 hover:bg-slate-700 text-rose-400 font-bold px-4 py-2.5 rounded-xl transition text-sm">
                                Reset
                            </button>
                        </div>
                        <!-- Lap List -->
                        <div class="w-full mt-4 pt-3 border-t border-slate-800 text-xs max-h-32 overflow-y-auto space-y-1" id="swLapList">
                            <!-- Laps inserted here -->
                        </div>
                    </div>

                    <!-- COUNTDOWN TIMER 60 DETIK -->
                    <div class="bg-slate-900 text-white rounded-2xl p-6 flex flex-col items-center justify-between shadow-lg">
                        <div class="w-full flex justify-between items-center text-xs text-slate-400 font-semibold mb-2">
                            <span>TIMER HITUNG MUNDUR (SIT-UP 60 DETIK)</span>
                            <i class="fa-solid fa-hourglass-half text-amber-400"></i>
                        </div>
                        <div class="my-6 text-center">
                            <span id="cdDisplay" class="font-mono text-5xl sm:text-6xl font-extrabold tracking-wider text-amber-400">01:00</span>
                        </div>
                        <div class="flex space-x-3 w-full">
                            <button id="cdStartBtn" onclick="toggleCountdown()" class="flex-1 bg-amber-500 hover:bg-amber-600 text-slate-950 font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center space-x-1">
                                <i class="fa-solid fa-play"></i> <span id="cdStartText">Start 60s</span>
                            </button>
                            <button onclick="resetCountdown()" class="bg-slate-800 hover:bg-slate-700 text-amber-400 font-bold px-4 py-2.5 rounded-xl transition text-sm">
                                Reset
                            </button>
                        </div>
                        <div class="w-full mt-4 pt-3 border-t border-slate-800 text-xs text-slate-400 text-center">
                            Suara alarm pemicu otomatis berbunyi saat timer 60s selesai.
                        </div>
                    </div>

                </div>
            </div>
        </section>

        <!-- TAB 4: MATERI EDUKASI & NORMA STANDAR -->
        <section id="sec-materi" class="hidden space-y-6">
            <div class="bg-white rounded-2xl p-5 sm:p-6 shadow-sm border border-slate-200">
                <div class="mb-6 border-b border-slate-100 pb-4">
                    <h2 class="text-xl font-bold text-slate-800 flex items-center gap-2">
                        <i class="fa-solid fa-book-bookmark text-amber-600"></i>
                        Panduan Materi & Standar Norma TKJI (Usia 13-15 Tahun)
                    </h2>
                    <p class="text-sm text-slate-500">Pedoman kurikulum PJOK Kelas 7 SMP untuk 4 komponen kebugaran jasmani utama.</p>
                </div>

                <!-- Accordion / Grid Theory -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    
                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-200 space-y-2">
                        <h3 class="font-bold text-blue-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-bolt text-blue-600"></i> 1. Kecepatan (Speed)
                        </h3>
                        <p class="text-xs text-slate-600 justify-text leading-relaxed">
                            <strong>Pengertian:</strong> Kemampuan tubuh untuk melakukan gerakan secara berkesinambungan dalam waktu sesingkat-singkatnya.
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Bentuk Tes:</strong> Lari Sprint 50 Meter di lintasan lurus.
                        </p>
                        <div class="text-xs bg-white p-2.5 rounded-lg border border-blue-100">
                            <span class="font-bold text-blue-800">Manfaat Bagi Siswa SMP:</span> Meningkatkan reaksi, efisiensi kerja otot tungkai, dan ketangkasan olahraga cabang atletik/permainan bola.
                        </div>
                    </div>

                    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-200 space-y-2">
                        <h3 class="font-bold text-indigo-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-arrows-split-up-and-left text-indigo-600"></i> 2. Kelincahan (Agility)
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Pengertian:</strong> Kemampuan mengubah arah posisi tubuh secara cepat dan tepat tanpa kehilangan keseimbangan.
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Bentuk Tes:</strong> Shuttle Run (Lari Bolak-Balik) 4 x 10 Meter memindahkan atau menyentuh garis/patok.
                        </p>
                        <div class="text-xs bg-white p-2.5 rounded-lg border border-indigo-100">
                            <span class="font-bold text-indigo-800">Manfaat Bagi Siswa SMP:</span> Melatih koordinasi saraf-otot, kelenturan sendi, dan pencegahan cedera saat beraktivitas harian.
                        </div>
                    </div>

                    <div class="bg-emerald-50/50 p-4 rounded-xl border border-emerald-200 space-y-2">
                        <h3 class="font-bold text-emerald-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-dumbbell text-emerald-600"></i> 3. Kekuatan (Strength) & Daya Tahan Otot
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Pengertian:</strong> Tegangan yang dihasilkan otot ketika berkontraksi menahan beban tertentu.
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Bentuk Tes:</strong> Sit-Up (Baring Duduk) selama 60 Detik untuk daya tahan otot perut.
                        </p>
                        <div class="text-xs bg-white p-2.5 rounded-lg border border-emerald-100">
                            <span class="font-bold text-emerald-800">Manfaat Bagi Siswa SMP:</span> Memperkuat postur tubuh (tulang belakang), kestabilan inti tubuh (*core strength*), dan kapasitas fisik.
                        </div>
                    </div>

                    <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-200 space-y-2">
                        <h3 class="font-bold text-amber-900 text-sm flex items-center gap-2">
                            <i class="fa-solid fa-heart-pulse text-amber-600"></i> 4. Daya Tahan Jantung & Paru-Paru (Cardio)
                        </h3>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Pengertian:</strong> Kapasitas paru-paru dan sistem sirkulasi darah untuk menyalurkan oksigen secara efisien saat aktivitas fisik berat jangka panjang.
                        </p>
                        <p class="text-xs text-slate-600 leading-relaxed">
                            <strong>Bentuk Tes:</strong> Lari Jarak Sedang (1000m untuk Putra / 800m untuk Putri).
                        </p>
                        <div class="text-xs bg-white p-2.5 rounded-lg border border-amber-100">
                            <span class="font-bold text-amber-800">Manfaat Bagi Siswa SMP:</span> Meningkatkan stamina, kebugaran kardiovaskular, konsentrasi belajar, serta kekebalan tubuh.
                        </div>
                    </div>

                </div>

                <!-- NORMA SCORING TABLES -->
                <h3 class="text-base font-bold text-slate-800 mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-table text-blue-600"></i>
                    Tabel Norma Penilaian Tes Kebugaran Jasmani Indonesia (TKJI 13-15 Thn)
                </h3>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    
                    <!-- TABEL PUTRA -->
                    <div class="border border-blue-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-blue-600 text-white p-3 text-xs font-bold flex justify-between items-center">
                            <span>NORMA PUTRA (LAKI-LAKI) - KELAS 7 SMP</span>
                            <i class="fa-solid fa-mars text-sm"></i>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center text-slate-700">
                                <thead class="bg-blue-50 font-bold border-b border-blue-200 text-blue-900">
                                    <tr>
                                        <th class="p-2">Nilai</th>
                                        <th class="p-2">Lari 50m</th>
                                        <th class="p-2">Shuttle Run</th>
                                        <th class="p-2">Sit-Up 60s</th>
                                        <th class="p-2">Lari 1000m</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white text-[11px]">
                                    <tr class="bg-emerald-50/50"><td class="p-2 font-bold text-emerald-700">5 (Baik Sekali)</td><td>s.d 7.2"</td><td>s.d 10.5"</td><td>38x ke atas</td><td>s.d 3'47"</td></tr>
                                    <tr><td class="p-2 font-bold text-blue-700">4 (Baik)</td><td>7.3" - 8.0"</td><td>10.6" - 11.8"</td><td>28 - 37x</td><td>3'48" - 4'35"</td></tr>
                                    <tr class="bg-amber-50/50"><td class="p-2 font-bold text-amber-700">3 (Sedang)</td><td>8.1" - 9.0"</td><td>11.9" - 13.2"</td><td>19 - 27x</td><td>4'36" - 5'37"</td></tr>
                                    <tr><td class="p-2 font-bold text-orange-700">2 (Kurang)</td><td>9.1" - 10.3"</td><td>13.3" - 15.0"</td><td>8 - 18x</td><td>5'38" - 7'00"</td></tr>
                                    <tr class="bg-rose-50/50"><td class="p-2 font-bold text-rose-700">1 (Kurang Sekali)</td><td>> 10.4"</td><td>> 15.1"</td><td>0 - 7x</td><td>> 7'00"</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- TABEL PUTRI -->
                    <div class="border border-pink-200 rounded-xl overflow-hidden shadow-sm">
                        <div class="bg-pink-600 text-white p-3 text-xs font-bold flex justify-between items-center">
                            <span>NORMA PUTRI (PEREMPUAN) - KELAS 7 SMP</span>
                            <i class="fa-solid fa-venus text-sm"></i>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-xs text-center text-slate-700">
                                <thead class="bg-pink-50 font-bold border-b border-pink-200 text-pink-900">
                                    <tr>
                                        <th class="p-2">Nilai</th>
                                        <th class="p-2">Lari 50m</th>
                                        <th class="p-2">Shuttle Run</th>
                                        <th class="p-2">Sit-Up 60s</th>
                                        <th class="p-2">Lari 800m</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 bg-white text-[11px]">
                                    <tr class="bg-emerald-50/50"><td class="p-2 font-bold text-emerald-700">5 (Baik Sekali)</td><td>s.d 8.4"</td><td>s.d 11.5"</td><td>28x ke atas</td><td>s.d 3'38"</td></tr>
                                    <tr><td class="p-2 font-bold text-blue-700">4 (Baik)</td><td>8.5" - 9.3"</td><td>11.6" - 12.8"</td><td>20 - 27x</td><td>3'39" - 4'32"</td></tr>
                                    <tr class="bg-amber-50/50"><td class="p-2 font-bold text-amber-700">3 (Sedang)</td><td>9.4" - 10.5"</td><td>12.9" - 14.2"</td><td>11 - 19x</td><td>4'33" - 5'45"</td></tr>
                                    <tr><td class="p-2 font-bold text-orange-700">2 (Kurang)</td><td>10.6" - 11.9"</td><td>14.3" - 16.0"</td><td>4 - 10x</td><td>5'46" - 7'15"</td></tr>
                                    <tr class="bg-rose-50/50"><td class="p-2 font-bold text-rose-700">1 (Kurang Sekali)</td><td>> 12.0"</td><td>> 16.1"</td><td>0 - 3x</td><td>> 7'15"</td></tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

                <!-- Kategori Total Poin -->
                <div class="mt-6 bg-slate-100 p-4 rounded-xl text-xs space-y-2 border border-slate-200">
                    <h4 class="font-bold text-slate-800">Klasifikasi Total Nilai Kebugaran Jasmani (4 Aspek = 20 Poin Max):</h4>
                    <div class="grid grid-cols-2 sm:grid-cols-5 gap-2 text-center font-semibold">
                        <div class="bg-emerald-100 text-emerald-800 p-2 rounded-lg">17 - 20 : Baik Sekali</div>
                        <div class="bg-blue-100 text-blue-800 p-2 rounded-lg">13 - 16 : Baik</div>
                        <div class="bg-amber-100 text-amber-800 p-2 rounded-lg">9 - 12 : Sedang</div>
                        <div class="bg-orange-100 text-orange-800 p-2 rounded-lg">5 - 8 : Kurang</div>
                        <div class="bg-rose-100 text-rose-800 p-2 rounded-lg">0 - 4 : Kurang Sekali</div>
                    </div>
                </div>

            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 text-xs py-4 text-center border-t border-slate-800">
        <div class="max-w-7xl mx-auto px-4">
            Aplikasi Evaluasi Kebugaran Jasmani Kelas 7 SMP &copy; 2026 - Berdasarkan Standar TKJI PJOK Indonesia
        </div>
    </footer>

    <script>
        /* APP STATE MANAGEMENT */
        let currentGender = 'L'; // 'L' for Laki-laki, 'P' for Perempuan
        let records = JSON.parse(localStorage.getItem('fit7_records') || '[]');

        // Ambil daftar siswa dari data asli aplikasi (bukan ketik manual lagi) - sesuai kelas yang dipilih.
        let daftarSiswaKelasIni = [];
        async function muatSiswaKelas() {
            const kelas = document.getElementById('inputKelas').value;
            const selectNama = document.getElementById('inputNama');
            selectNama.innerHTML = '<option value="">Memuat...</option>';
            if (!kelas) {
                selectNama.innerHTML = '<option value="">- Pilih kelas dulu -</option>';
                return;
            }
            try {
                const respon = await fetch(`{{ url('/fit7-jasmani/siswa') }}/${encodeURIComponent(kelas)}`);
                daftarSiswaKelasIni = await respon.json();
                selectNama.innerHTML = '<option value="">- Pilih siswa -</option>' +
                    daftarSiswaKelasIni.map(s => `<option value="${s.nama}">${s.nama}</option>`).join('');
            } catch (e) {
                selectNama.innerHTML = '<option value="">Gagal memuat data siswa</option>';
            }
        }

        // Usia otomatis terisi dari tanggal lahir siswa (kalau datanya ada) begitu nama dipilih -
        // TAPI dropdown usianya tetap bisa diganti manual oleh guru kalau memang perlu.
        function isiUsiaOtomatis() {
            const namaTerpilih = document.getElementById('inputNama').value;
            const siswa = daftarSiswaKelasIni.find(s => s.nama === namaTerpilih);
            if (siswa && siswa.usia) {
                const selectUsia = document.getElementById('inputUsia');
                const adaOpsi = Array.from(selectUsia.options).some(o => o.value == siswa.usia);
                if (!adaOpsi) {
                    const opsiBaru = document.createElement('option');
                    opsiBaru.value = siswa.usia;
                    opsiBaru.textContent = `${siswa.usia} Tahun (otomatis dari tanggal lahir)`;
                    selectUsia.insertBefore(opsiBaru, selectUsia.firstChild);
                }
                selectUsia.value = siswa.usia;
            }
        }
        let myChart = null;

        // Audio Context for Beep Sound
        const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
        function playBeep(freq = 800, type = 'sine', duration = 0.2) {
            try {
                if (audioCtx.state === 'suspended') {
                    audioCtx.resume();
                }
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.type = type;
                osc.frequency.value = freq;
                gain.gain.setValueAtTime(0.15, audioCtx.currentTime);
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.start();
                osc.stop(audioCtx.currentTime + duration);
            } catch(e) {
                console.log("Audio play error", e);
            }
        }

        /* TAB NAVIGATION SWITCHING */
        function switchTab(tabName) {
            ['kalkulator', 'rekap', 'timer', 'materi'].forEach(t => {
                const sec = document.getElementById('sec-' + t);
                const btn = document.getElementById('tab-' + t);
                if (t === tabName) {
                    sec.classList.remove('hidden');
                    btn.classList.add('active');
                } else {
                    sec.classList.add('hidden');
                    btn.classList.remove('active');
                }
            });
            if (tabName === 'rekap') {
                renderTable();
            }
        }

        /* GENDER TOGGLE */
        function setGender(gender) {
            currentGender = gender;
            const btnL = document.getElementById('btn-gender-L');
            const btnP = document.getElementById('btn-gender-P');
            const labelDayaTahan = document.getElementById('labelDayaTahan');

            if (gender === 'L') {
                btnL.className = "px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm bg-blue-600 text-white";
                btnP.className = "px-4 py-2 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:bg-white";
                labelDayaTahan.textContent = "Lari 1000 Meter (Putra)";
            } else {
                btnP.className = "px-4 py-2 rounded-lg text-sm font-semibold transition-all shadow-sm bg-pink-600 text-white";
                btnL.className = "px-4 py-2 rounded-lg text-sm font-semibold transition-all text-slate-600 hover:bg-white";
                labelDayaTahan.textContent = "Lari 800 Meter (Putri)";
            }
            liveUpdateCalc();
        }

        /* NORMATIVE SCORING ALGORITHMS (TKJI Usia 13-15) */
        
        // 1. Kecepatan (Lari 50m) in Seconds
        function scoreKecepatan(val, gender) {
            if (!val || val <= 0) return 0;
            if (gender === 'L') {
                if (val <= 7.2) return 5;
                if (val <= 8.0) return 4;
                if (val <= 9.0) return 3;
                if (val <= 10.3) return 2;
                return 1;
            } else { // Perempuan
                if (val <= 8.4) return 5;
                if (val <= 9.3) return 4;
                if (val <= 10.5) return 3;
                if (val <= 11.9) return 2;
                return 1;
            }
        }

        // 2. Kelincahan (Shuttle Run 4x10m) in Seconds
        function scoreKelincahan(val, gender) {
            if (!val || val <= 0) return 0;
            if (gender === 'L') {
                if (val <= 10.5) return 5;
                if (val <= 11.8) return 4;
                if (val <= 13.2) return 3;
                if (val <= 15.0) return 2;
                return 1;
            } else { // Perempuan
                if (val <= 11.5) return 5;
                if (val <= 12.8) return 4;
                if (val <= 14.2) return 3;
                if (val <= 16.0) return 2;
                return 1;
            }
        }

        // 3. Kekuatan (Sit-up 60 Detik) in Reps
        function scoreKekuatan(val, gender) {
            if (val === '' || val === null || val < 0) return 0;
            const reps = parseInt(val);
            if (gender === 'L') {
                if (reps >= 38) return 5;
                if (reps >= 28) return 4;
                if (reps >= 19) return 3;
                if (reps >= 8) return 2;
                return 1;
            } else { // Perempuan
                if (reps >= 28) return 5;
                if (reps >= 20) return 4;
                if (reps >= 11) return 3;
                if (reps >= 4) return 2;
                return 1;
            }
        }

        // 4. Jantung & Paru-Paru (Cardio Lari 1000m Putra / 800m Putri) Total Seconds
        function scoreCardio(totalSec, gender) {
            if (!totalSec || totalSec <= 0) return 0;
            if (gender === 'L') {
                // 1000m Putra
                // < 3'47" (227s) = 5
                // 3'48" - 4'35" (228 - 275s) = 4
                // 4'36" - 5'37" (276 - 337s) = 3
                // 5'38" - 7'00" (338 - 420s) = 2
                // > 7'00" (> 420s) = 1
                if (totalSec <= 227) return 5;
                if (totalSec <= 275) return 4;
                if (totalSec <= 337) return 3;
                if (totalSec <= 420) return 2;
                return 1;
            } else {
                // 800m Putri
                // < 3'38" (218s) = 5
                // 3'39" - 4'32" (219 - 272s) = 4
                // 4'33" - 5'45" (273 - 345s) = 3
                // 5'46" - 7'15" (346 - 435s) = 2
                // > 7'15" (> 435s) = 1
                if (totalSec <= 218) return 5;
                if (totalSec <= 272) return 4;
                if (totalSec <= 345) return 3;
                if (totalSec <= 435) return 2;
                return 1;
            }
        }

        // Get Category Title
        function getKategori(totalPoin) {
            if (totalPoin >= 17) return { label: 'Baik Sekali', class: 'bg-emerald-500 text-white border-emerald-400' };
            if (totalPoin >= 13) return { label: 'Baik', class: 'bg-blue-500 text-white border-blue-400' };
            if (totalPoin >= 9) return { label: 'Sedang', class: 'bg-amber-500 text-white border-amber-400' };
            if (totalPoin >= 5) return { label: 'Kurang', class: 'bg-orange-500 text-white border-orange-400' };
            if (totalPoin > 0) return { label: 'Kurang Sekali', class: 'bg-rose-500 text-white border-rose-400' };
            return { label: 'Belum Lengkap', class: 'bg-slate-800 text-slate-300 border-slate-700' };
        }

        /* LIVE CALCULATOR UPDATE */
        function liveUpdateCalc() {
            const valKec = parseFloat(document.getElementById('valKecepatan').value);
            const valKel = parseFloat(document.getElementById('valKelincahan').value);
            const valKek = document.getElementById('valKekuatan').value;
            const valMin = parseInt(document.getElementById('valCardioMin').value) || 0;
            const valSec = parseInt(document.getElementById('valCardioSec').value) || 0;
            const totalCardioSec = (valMin * 60) + valSec;

            const sKec = scoreKecepatan(valKec, currentGender);
            const sKel = scoreKelincahan(valKel, currentGender);
            const sKek = scoreKekuatan(valKek, currentGender);
            const sCar = scoreCardio(totalCardioSec, currentGender);

            document.getElementById('scoreKecepatan').textContent = sKec ? sKec + ' / 5' : '-';
            document.getElementById('scoreKelincahan').textContent = sKel ? sKel + ' / 5' : '-';
            document.getElementById('scoreKekuatan').textContent = sKek ? sKek + ' / 5' : '-';
            document.getElementById('scoreCardio').textContent = sCar ? sCar + ' / 5' : '-';

            document.getElementById('breakKecepatan').textContent = sKec ? sKec + ' poin' : '-';
            document.getElementById('breakKelincahan').textContent = sKel ? sKel + ' poin' : '-';
            document.getElementById('breakKekuatan').textContent = sKek ? sKek + ' poin' : '-';
            document.getElementById('breakCardio').textContent = sCar ? sCar + ' poin' : '-';

            let totalPoin = 0;
            let countValid = 0;
            if (sKec) { totalPoin += sKec; countValid++; }
            if (sKel) { totalPoin += sKel; countValid++; }
            if (sKek) { totalPoin += sKek; countValid++; }
            if (sCar) { totalPoin += sCar; countValid++; }

            document.getElementById('displayTotalSkor').textContent = totalPoin;

            const katInfo = getKategori(countValid === 4 ? totalPoin : 0);
            const badge = document.getElementById('badgeKategori');
            badge.textContent = countValid === 4 ? katInfo.label : `Data ${countValid}/4 Terisi`;
            badge.className = `inline-block px-3 py-1.5 rounded-lg font-bold text-sm border ${katInfo.class}`;

            updateRadarChart([sKec, sKel, sKek, sCar]);
            generateSaran([sKec, sKel, sKek, sCar]);
        }

        /* RADAR CHART INITIALIZATION & UPDATES */
        function initRadarChart() {
            const ctx = document.getElementById('radarChart').getContext('2d');
            myChart = new Chart(ctx, {
                type: 'radar',
                data: {
                    labels: ['Kecepatan (Sprint)', 'Kelincahan (Shuttle)', 'Kekuatan (Sit-Up)', 'Cardio (Jantung-Paru)'],
                    datasets: [{
                        label: 'Nilai Kebugaran Siswa (1 - 5)',
                        data: [0, 0, 0, 0],
                        backgroundColor: 'rgba(37, 99, 235, 0.25)',
                        borderColor: '#2563eb',
                        borderWidth: 2,
                        pointBackgroundColor: '#1d4ed8',
                        pointBorderColor: '#fff',
                        pointHoverBackgroundColor: '#fff',
                        pointHoverBorderColor: '#1d4ed8'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        r: {
                            angleLines: { color: '#e2e8f0' },
                            grid: { color: '#cbd5e1' },
                            pointLabels: {
                                font: { size: 10, weight: 'bold' },
                                color: '#334155'
                            },
                            suggestedMin: 0,
                            suggestedMax: 5,
                            ticks: { stepSize: 1 }
                        }
                    },
                    plugins: {
                        legend: { display: false }
                    }
                }
            });
        }

        function updateRadarChart(scores) {
            if (!myChart) return;
            myChart.data.datasets[0].data = scores;
            myChart.update();
        }

        /* GENERATE ADAPTIVE SUGGESTIONS */
        function generateSaran(scores) {
            const saranBox = document.getElementById('saranPengembangan');
            const labels = ['Kecepatan', 'Kelincahan', 'Kekuatan', 'Daya Tahan Jantung & Paru'];
            let saranList = [];

            scores.forEach((s, idx) => {
                if (s > 0 && s <= 2) {
                    if (idx === 0) saranList.push("<strong>Kecepatan Kurang:</strong> Latih *Sprint Bound*, lari akselerasi jarak pendek (10-20m), dan latihan frekuensi langkah kaki (*fast feet*).");
                    if (idx === 1) saranList.push("<strong>Kelincahan Kurang:</strong> Latihan *Ladder Drill*, lari zig-zag memutar patok, dan lompat tangkas ke samping.");
                    if (idx === 2) saranList.push("<strong>Kekuatan Kurang:</strong> Lakukan rutin *Sit-Up*, *Plank* 30 detik, dan *Crushes* 3-4 kali seminggu.");
                    if (idx === 3) saranList.push("<strong>Daya Tahan Kurang:</strong> Latihan *Jogging* ritme sedang 15-20 menit berturut-turut atau latihan *Interval Running*.");
                }
            });

            if (saranList.length > 0) {
                saranBox.innerHTML = `<i class="fa-solid fa-triangle-exclamation text-amber-500 me-1"></i> <strong>Rekomendasi Latihan Fisik Khusus:</strong><ul class="list-disc ml-5 mt-1 space-y-1">${saranList.map(item => `<li>${item}</li>`).join('')}</ul>`;
            } else if (scores.every(s => s >= 4)) {
                saranBox.innerHTML = `<i class="fa-solid fa-circle-check text-emerald-500 me-1"></i> <strong>Luar Biasa!</strong> Kebugaran jasmani siswa tergolong sangat prima di seluruh aspek. Pertahankan dengan olahraga teratur dan gizi seimbang.`;
            } else {
                saranBox.innerHTML = `<i class="fa-solid fa-lightbulb text-amber-500 me-1"></i> <strong>Petunjuk:</strong> Isikan seluruh data hasil tes untuk melihat profil kebugaran dan saran rekomendasi latihan.`;
            }
        }

        /* SAVE RECORD TO LOCAL STORAGE */
        function calculateAndSave(e) {
            e.preventDefault();
            const nama = document.getElementById('inputNama').value.trim();
            const kelas = document.getElementById('inputKelas').value.trim() || 'VII';
            const usia = document.getElementById('inputUsia').value;

            const valKec = parseFloat(document.getElementById('valKecepatan').value);
            const valKel = parseFloat(document.getElementById('valKelincahan').value);
            const valKek = parseInt(document.getElementById('valKekuatan').value);
            const valMin = parseInt(document.getElementById('valCardioMin').value) || 0;
            const valSec = parseInt(document.getElementById('valCardioSec').value) || 0;
            const totalCardioSec = (valMin * 60) + valSec;

            const sKec = scoreKecepatan(valKec, currentGender);
            const sKel = scoreKelincahan(valKel, currentGender);
            const sKek = scoreKekuatan(valKek, currentGender);
            const sCar = scoreCardio(totalCardioSec, currentGender);

            const totalPoin = sKec + sKel + sKek + sCar;
            const katObj = getKategori(totalPoin);

            const recordItem = {
                id: Date.now(),
                nama,
                kelas,
                usia,
                gender: currentGender,
                valKec,
                valKel,
                valKek,
                cardioFormatted: `${valMin}'${valSec}"`,
                cardioSec: totalCardioSec,
                sKec,
                sKel,
                sKek,
                sCar,
                totalPoin,
                kategori: katObj.label,
                tanggal: new Date().toLocaleDateString('id-ID')
            };

            records.unshift(recordItem);
            localStorage.setItem('fit7_records', JSON.stringify(records));

            playBeep(900, 'sine', 0.15);
            alert(`Data kebugaran atas nama ${nama} berhasil disimpan ke Rekap Kelas!`);
            resetForm();
            switchTab('rekap');
        }

        function resetForm() {
            document.getElementById('fitnessForm').reset();
            liveUpdateCalc();
        }

        /* RENDER TABLE DATA */
        function renderTable() {
            const tbody = document.getElementById('tableBodyData');
            const search = document.getElementById('searchSiswa').value.toLowerCase();
            const filterGen = document.getElementById('filterGender').value;
            const filterKat = document.getElementById('filterKategori').value;
            const emptyNotice = document.getElementById('emptyTableNotice');

            tbody.innerHTML = '';

            const filtered = records.filter(r => {
                const matchName = r.nama.toLowerCase().includes(search);
                const matchGen = (filterGen === 'ALL') || (r.gender === filterGen);
                const matchKat = (filterKat === 'ALL') || (r.kategori === filterKat);
                return matchName && matchGen && matchKat;
            });

            if (filtered.length === 0) {
                emptyNotice.classList.remove('hidden');
            } else {
                emptyNotice.classList.add('hidden');
                filtered.forEach((r, idx) => {
                    const badgeKat = getKategori(r.totalPoin);
                    const tr = document.createElement('tr');
                    tr.className = "hover:bg-slate-50 transition";
                    tr.innerHTML = `
                        <td class="p-3 font-semibold text-slate-400">${idx + 1}</td>
                        <td class="p-3 font-bold text-slate-800">${r.nama}<div class="text-[10px] text-slate-400 font-normal">${r.kelas} • ${r.tanggal}</div></td>
                        <td class="p-3"><span class="px-2 py-0.5 rounded text-[10px] font-bold ${r.gender === 'L' ? 'bg-blue-100 text-blue-800' : 'bg-pink-100 text-pink-800'}">${r.gender === 'L' ? 'Putra' : 'Putri'}</span></td>
                        <td class="p-3 text-center"><strong>${r.valKec}s</strong> <span class="text-slate-400 text-[10px]">(${r.sKec})</span></td>
                        <td class="p-3 text-center"><strong>${r.valKel}s</strong> <span class="text-slate-400 text-[10px]">(${r.sKel})</span></td>
                        <td class="p-3 text-center"><strong>${r.valKek}x</strong> <span class="text-slate-400 text-[10px]">(${r.sKek})</span></td>
                        <td class="p-3 text-center"><strong>${r.cardioFormatted}</strong> <span class="text-slate-400 text-[10px]">(${r.sCar})</span></td>
                        <td class="p-3 text-center font-extrabold text-blue-700 text-sm">${r.totalPoin}</td>
                        <td class="p-3 text-center"><span class="px-2.5 py-1 rounded-full text-[10px] font-bold ${badgeKat.class}">${r.kategori}</span></td>
                        <td class="p-3 text-center">
                            <button onclick="deleteRecord(${r.id})" class="text-rose-500 hover:text-rose-700 p-1.5 rounded" title="Hapus">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            }
        }

        function deleteRecord(id) {
            if (confirm("Apakah Anda yakin ingin menghapus data siswa ini?")) {
                records = records.filter(r => r.id !== id);
                localStorage.setItem('fit7_records', JSON.stringify(records));
                renderTable();
            }
        }

        function clearAllData() {
            if (confirm("PERINGATAN: Semua rekap data kebugaran siswa akan dihapus permanen! Lanjutkan?")) {
                records = [];
                localStorage.removeItem('fit7_records');
                renderTable();
            }
        }

        function exportToCSV() {
            if (records.length === 0) {
                alert("Tidak ada data untuk diexport!");
                return;
            }
            let csvContent = "data:text/csv;charset=utf-8,";
            csvContent += "No,Nama,Kelas,Usia,Jenis Kelamin,Lari 50m (s),Poin 50m,Shuttle Run (s),Poin Shuttle,SitUp 60s (x),Poin SitUp,Lari Jarak (m:s),Poin Lari,Total Poin,Kategori,Tanggal\n";

            records.forEach((r, i) => {
                const row = [
                    i + 1,
                    `"${r.nama}"`,
                    `"${r.kelas}"`,
                    r.usia,
                    r.gender === 'L' ? 'Laki-Laki' : 'Perempuan',
                    r.valKec,
                    r.sKec,
                    r.valKel,
                    r.sKel,
                    r.valKek,
                    r.sKek,
                    `"${r.cardioFormatted}"`,
                    r.sCar,
                    r.totalPoin,
                    `"${r.kategori}"`,
                    r.tanggal
                ].join(",");
                csvContent += row + "\n";
            });

            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", `Rekap_Kebugaran_Jasmani_Kelas7_${new Date().toISOString().slice(0,10)}.csv`);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        /* STOPWATCH LOGIC */
        let swTimer = null;
        let swStartTime = 0;
        let swElapsedTime = 0;
        let swRunning = false;
        let swLapCount = 0;

        function toggleStopwatch() {
            if (!swRunning) {
                swStartTime = Date.now() - swElapsedTime;
                swTimer = setInterval(updateStopwatch, 10);
                swRunning = true;
                document.getElementById('swStartText').textContent = 'Pause';
                document.getElementById('swStartBtn').className = "flex-1 bg-amber-500 hover:bg-amber-600 text-white font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center space-x-1";
                playBeep(700, 'sine', 0.1);
            } else {
                clearInterval(swTimer);
                swRunning = false;
                document.getElementById('swStartText').textContent = 'Lanjut';
                document.getElementById('swStartBtn').className = "flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center space-x-1";
            }
        }

        function updateStopwatch() {
            swElapsedTime = Date.now() - swStartTime;
            document.getElementById('swDisplay').textContent = formatTimeMS(swElapsedTime);
        }

        function resetStopwatch() {
            clearInterval(swTimer);
            swRunning = false;
            swElapsedTime = 0;
            swLapCount = 0;
            document.getElementById('swDisplay').textContent = "00:00.00";
            document.getElementById('swStartText').textContent = 'Mulai';
            document.getElementById('swStartBtn').className = "flex-1 bg-emerald-500 hover:bg-emerald-600 text-white font-bold py-2.5 rounded-xl transition text-sm flex items-center justify-center space-x-1";
            document.getElementById('swLapList').innerHTML = '';
        }

        function lapStopwatch() {
            if (!swRunning) return;
            swLapCount++;
            const lapTime = formatTimeMS(swElapsedTime);
            const list = document.getElementById('swLapList');
            const item = document.createElement('div');
            item.className = "flex justify-between text-slate-300 py-0.5 border-b border-slate-800";
            item.innerHTML = `<span>Lap ${swLapCount}</span><span class="font-mono font-bold text-emerald-400">${lapTime}</span>`;
            list.prepend(item);
        }

        function formatTimeMS(ms) {
            const minutes = Math.floor(ms / 60000);
            const seconds = Math.floor((ms % 60000) / 1000);
            const centi = Math.floor((ms % 1000) / 10);
            return `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}.${String(centi).padStart(2, '0')}`;
        }

        /* COUNTDOWN 60S TIMER LOGIC */
        let cdTimer = null;
        let cdTimeLeft = 60;
        let cdRunning = false;

        function toggleCountdown() {
            if (!cdRunning) {
                if (cdTimeLeft <= 0) cdTimeLeft = 60;
                cdTimer = setInterval(updateCountdown, 1000);
                cdRunning = true;
                document.getElementById('cdStartText').textContent = 'Pause';
                playBeep(800, 'triangle', 0.15);
            } else {
                clearInterval(cdTimer);
                cdRunning = false;
                document.getElementById('cdStartText').textContent = 'Lanjut';
            }
        }

        function updateCountdown() {
            cdTimeLeft--;
            document.getElementById('cdDisplay').textContent = `00:${String(cdTimeLeft).padStart(2, '0')}`;
            
            if (cdTimeLeft <= 3 && cdTimeLeft > 0) {
                playBeep(600, 'sine', 0.1);
            }

            if (cdTimeLeft <= 0) {
                clearInterval(cdTimer);
                cdRunning = false;
                document.getElementById('cdStartText').textContent = 'Start 60s';
                playBeep(1200, 'square', 0.6);
                alert("WAKTU HABIS! (60 Detik Tes Sit-Up Selesai)");
            }
        }

        function resetCountdown() {
            clearInterval(cdTimer);
            cdRunning = false;
            cdTimeLeft = 60;
            document.getElementById('cdDisplay').textContent = "01:00";
            document.getElementById('cdStartText').textContent = 'Start 60s';
        }

        /* INITIALIZATION ON LOAD */
        window.onload = function() {
            initRadarChart();
            liveUpdateCalc();
        };
    </script>

</div>

<script id="landing-controller">
(function(){
  const app = document.getElementById('app');
  if(!app) return;
  const back = document.createElement('div');
  back.className='app-back';
  back.innerHTML='<a href="#landing-page"><i class="fas fa-home"></i> Kembali ke Halaman Utama</a>';
  app.parentNode.insertBefore(back, app);
  function setMode(){
    const isApp = location.hash === '#app';
    document.body.classList.toggle('app-mode', isApp);
    if(isApp) setTimeout(()=>app.scrollIntoView({behavior:'smooth'}),40);
  }
  window.addEventListener('hashchange', setMode);
  setMode();
})();
</script>
</body>
</html>