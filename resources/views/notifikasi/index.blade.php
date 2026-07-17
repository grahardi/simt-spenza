@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-bell me-2"></i>Notifikasi</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($notifikasi->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-bell-slash me-1"></i> Tidak ada notifikasi.
        </div>
    @else
        @foreach ($notifikasi as $n)
            <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <span class="menu-icon {{ $n->belumDitanggapi() ? 'bg-red' : 'bg-teal' }}" style="width:40px;height:40px;font-size:16px;flex-shrink:0;">
                    <i class="fas {{ $n->belumDitanggapi() ? 'fa-exclamation-triangle' : 'fa-check' }}"></i>
                </span>
                <div class="flex-grow-1">
                    <div class="fw-semibold">
                        {{ $n->kategori }}
                        @if ($n->belumDitanggapi())
                            <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Belum ditanggapi</span>
                        @else
                            <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">{{ $n->aksi }}</span>
                        @endif
                    </div>
                    <div class="text-muted small">{{ $n->keterangan }}</div>
                    <div class="text-muted small">
                        Kelas {{ $n->kelas }} &middot; {{ $n->tgl_warning?->translatedFormat('d F Y') }}
                        @if ($n->pelapor) &middot; oleh {{ $n->pelapor->nama }} @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
