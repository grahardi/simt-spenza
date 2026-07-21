@extends('layouts.app')

@section('title', 'Tambah Surat Keluar')

@section('content')
@include('persuratan._menu')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-plus me-2"></i>Tambah Surat Keluar</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    <p class="text-muted mb-4">Pilih jenis surat yang mau dibuat:</p>

    <div class="row g-3">
        <div class="col-md-4">
            <a href="{{ route('surat-tu.sppd.create') }}" class="text-decoration-none">
                <div class="border rounded p-4 text-center h-100" style="transition:.15s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='none'">
                    <i class="fas fa-file-signature fa-2x text-primary mb-2"></i>
                    <h6 class="mb-1">SPPD</h6>
                    <p class="text-muted small mb-0">Surat Tugas &amp; Perjalanan Dinas - otomatis digenerate dari data guru</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('surat-tu.permohonan.create') }}" class="text-decoration-none">
                <div class="border rounded p-4 text-center h-100" style="transition:.15s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='none'">
                    <i class="fas fa-file-alt fa-2x text-warning mb-2"></i>
                    <h6 class="mb-1">Surat Permohonan</h6>
                    <p class="text-muted small mb-0">Surat permohonan ke pihak luar - otomatis digenerate dari template</p>
                </div>
            </a>
        </div>
        <div class="col-md-4">
            <a href="{{ route('surat-keluar.create') }}" class="text-decoration-none">
                <div class="border rounded p-4 text-center h-100" style="transition:.15s;" onmouseover="this.style.boxShadow='0 4px 12px rgba(0,0,0,.1)'" onmouseout="this.style.boxShadow='none'">
                    <i class="fas fa-archive fa-2x text-secondary mb-2"></i>
                    <h6 class="mb-1">Arsip Saja</h6>
                    <p class="text-muted small mb-0">Surat sudah jadi dari luar sistem - tinggal upload berkas &amp; isi keterangan (tidak digenerate)</p>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
