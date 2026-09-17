@extends('layouts.app')

@section('title', 'Kartu Ujian')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-id-card me-2"></i>Kartu Ujian & Denah Tempat Duduk</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-1"></i> Data nama, kelas, dan foto diambil otomatis dari Data Siswa & Upload Foto Kelas.
    Data <strong>Password, Ruang, No Kursi</strong> perlu diisi manual lewat Import Excel karena belum ada di sistem.
    Progres: <strong>{{ $jumlahSudahDiisi }}</strong> dari <strong>{{ $jumlahSiswa }}</strong> siswa sudah diisi data ujiannya.
</div>

<div class="menu-grid">
    <a href="{{ route('kartu-ujian.import') }}" class="menu-card bg-blue">
        <span class="menu-icon"><i class="fas fa-file-excel"></i></span>
        <span class="menu-title">Import Data (Excel)</span>
    </a>
    <a href="{{ route('kartu-ujian.cetak-semua-denah') }}" class="menu-card bg-amber" target="_blank">
        <span class="menu-icon"><i class="fas fa-th"></i></span>
        <span class="menu-title">Cetak Semua Denah</span>
    </a>
    <a href="{{ route('kartu-ujian.pengaturan-denah') }}" class="menu-card bg-purple">
        <span class="menu-icon"><i class="fas fa-cogs"></i></span>
        <span class="menu-title">Pengaturan Tipe Denah</span>
    </a>
    <a href="{{ route('kartu-ujian.pengaturan-kartu') }}" class="menu-card bg-coral">
        <span class="menu-icon"><i class="fas fa-heading"></i></span>
        <span class="menu-title">Pengaturan Judul &amp; Tanggal</span>
    </a>
</div>

<div class="p-4 bg-white rounded shadow mt-3">
    <h6 class="mb-3"><i class="fas fa-id-card me-1"></i> Cetak Kartu Ujian / Label Meja</h6>
    <p class="text-muted small">Bisa cetak semua sekaligus, atau pilih per ruang / per kelas saja.</p>
    <form method="GET" class="row g-2 align-items-end" id="formCetakKartu">
        <div class="col-md-4">
            <label class="form-label small mb-1">Filter</label>
            <select id="pilihFilter" class="form-select" onchange="ubahFilterCetak()">
                <option value="">Semua Peserta</option>
                <option value="ruang">Per Ruang</option>
                <option value="kelas">Per Kelas</option>
            </select>
        </div>
        <div class="col-md-4" id="wadahPilihRuang" style="display:none;">
            <label class="form-label small mb-1">Ruang</label>
            <select name="ruang" class="form-select" disabled>
                @foreach ($daftarRuang as $r)
                    <option value="{{ $r }}">Ruang {{ $r }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4" id="wadahPilihKelas" style="display:none;">
            <label class="form-label small mb-1">Kelas</label>
            <select name="kelas" class="form-select" disabled>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}">{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4 d-flex gap-2">
            <button type="submit" formaction="{{ route('kartu-ujian.cetak-kartu') }}" formtarget="_blank" class="btn btn-success">
                <i class="fas fa-id-card me-1"></i> Cetak Kartu
            </button>
            <button type="submit" formaction="{{ route('kartu-ujian.cetak-label') }}" formtarget="_blank" class="btn btn-outline-secondary">
                <i class="fas fa-tag me-1"></i> Cetak Label
            </button>
        </div>
    </form>
</div>

<script>
function ubahFilterCetak() {
    const pilihan = document.getElementById('pilihFilter').value;
    document.getElementById('wadahPilihRuang').style.display = pilihan === 'ruang' ? 'block' : 'none';
    document.getElementById('wadahPilihKelas').style.display = pilihan === 'kelas' ? 'block' : 'none';
    document.querySelector('#wadahPilihRuang select').disabled = pilihan !== 'ruang';
    document.querySelector('#wadahPilihKelas select').disabled = pilihan !== 'kelas';
}
</script>

@if ($daftarRuang->isNotEmpty())
    <div class="p-4 bg-white rounded shadow mt-3">
        <h6 class="mb-3">Denah per Ruang</h6>
        <div class="d-flex flex-wrap gap-2">
            @foreach ($daftarRuang as $ruang)
                <a href="{{ route('kartu-ujian.denah', $ruang) }}" class="btn btn-outline-secondary btn-sm" target="_blank">
                    Ruang {{ $ruang }}
                </a>
            @endforeach
        </div>
    </div>
@endif
@endsection
