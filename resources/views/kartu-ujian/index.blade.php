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
    <a href="{{ route('kartu-ujian.cetak-kartu') }}" class="menu-card bg-green" target="_blank">
        <span class="menu-icon"><i class="fas fa-id-card"></i></span>
        <span class="menu-title">Cetak Kartu Ujian</span>
    </a>
    <a href="{{ route('kartu-ujian.cetak-label') }}" class="menu-card bg-teal" target="_blank">
        <span class="menu-icon"><i class="fas fa-tag"></i></span>
        <span class="menu-title">Cetak Label Meja</span>
    </a>
    <a href="{{ route('kartu-ujian.cetak-semua-denah') }}" class="menu-card bg-amber" target="_blank">
        <span class="menu-icon"><i class="fas fa-th"></i></span>
        <span class="menu-title">Cetak Semua Denah</span>
    </a>
    <a href="{{ route('kartu-ujian.pengaturan-denah') }}" class="menu-card bg-purple">
        <span class="menu-icon"><i class="fas fa-cogs"></i></span>
        <span class="menu-title">Pengaturan Tipe Denah</span>
    </a>
</div>

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
