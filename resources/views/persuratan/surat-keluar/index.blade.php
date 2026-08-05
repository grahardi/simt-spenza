@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
@include('persuratan._menu')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-paper-plane fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Surat Keluar</h1>
    </div>
    <a href="{{ route('surat-keluar.pilih-jenis') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Surat
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari perihal, tujuan, atau kode surat..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

@if ($surat->isEmpty())
    <div class="bg-white rounded shadow text-muted text-center py-4">
        <i class="far fa-question-circle me-1"></i> Belum ada surat keluar tercatat.
    </div>
@else
    @php
        $warnaKategori = fn ($perihal) => str_starts_with($perihal ?? '', 'SPPD') ? 'blue' : (str_starts_with($perihal ?? '', 'Permohonan') ? 'amber' : 'purple');
    @endphp
    <div class="menu-grid">
        @foreach ($surat as $s)
            <a href="{{ route('surat-keluar.show', $s) }}" class="menu-card bg-{{ $warnaKategori($s->perihal) }}">
                <span class="menu-icon">
                    <i class="fas fa-{{ str_starts_with($s->perihal ?? '', 'SPPD') ? 'plane-departure' : (str_starts_with($s->perihal ?? '', 'Permohonan') ? 'file-alt' : 'archive') }}"></i>
                </span>
                <span class="menu-title">{{ $s->perihal }}</span>
                <span class="d-block small mt-1 opacity-75">{{ $s->tanggal_surat->format('d/m/Y') }} &middot; {{ $s->tujuan_surat }}</span>
                <code class="d-block small mt-1">{{ $s->kode_surat }}</code>
            </a>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $surat->onEachSide(1)->links() }}
    </div>
@endif
@endsection
