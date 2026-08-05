@extends('layouts.app')

@section('title', 'Ajuan Surat')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-signature fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Ajuan Surat - {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route('ajuan-surat.sppd.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Ajukan SPPD
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($daftar->isEmpty())
    <div class="bg-white rounded shadow text-muted text-center py-4">
        <i class="far fa-question-circle me-1"></i> Belum ada ajuan surat.
    </div>
@else
    @php
        $warnaJenis = ['sppd' => 'blue', 'surat_permohonan' => 'amber'];
    @endphp
    <div class="menu-grid">
        @foreach ($daftar as $a)
            <a href="{{ route('ajuan-surat.show', $a) }}" class="menu-card bg-{{ $warnaJenis[$a->jenis_surat] ?? 'purple' }}">
                <span class="menu-icon">
                    <i class="fas fa-{{ $a->jenis_surat === 'sppd' ? 'plane-departure' : 'file-alt' }}"></i>
                </span>
                <span class="menu-title">{{ $a->data['tema'] ?? $a->data['kegiatan'] ?? '-' }}</span>
                <span class="d-block small mt-1 opacity-75">{{ $a->created_at->translatedFormat('d M Y') }}</span>
                <span class="badge mt-2 {{ $a->status === 'selesai' ? 'bg-success' : ($a->status === 'diproses' ? 'bg-warning text-dark' : 'bg-light text-dark') }}">
                    {{ $a->labelStatus() }}
                </span>
            </a>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $daftar->onEachSide(1)->links() }}
    </div>
@endif
@endsection
