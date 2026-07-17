@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-bell me-2"></i>Notifikasi</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($notifikasi->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-bell-slash me-1"></i> Tidak ada notifikasi.
        </div>
    @else
        @foreach ($notifikasi as $n)
            @php $butuhKonfirmasi = $n->kategori === 'Kelas Kosong' && $n->belumDitanggapi(); @endphp
            <div class="d-flex align-items-start gap-3 py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <span class="menu-icon {{ $n->belumDitanggapi() ? 'bg-red' : 'bg-teal' }}" style="width:40px;height:40px;font-size:16px;flex-shrink:0;">
                    <i class="fas {{ $n->belumDitanggapi() ? 'fa-exclamation-triangle' : 'fa-check' }}"></i>
                </span>
                <div class="flex-grow-1">
                    <div class="fw-semibold">
                        {{ $n->kategori }}
                        @if ($n->kategori !== 'Kelas Kosong')
                            @if ($n->belumDitanggapi())
                                <span class="badge-status" style="background:#eef6fd;color:#185fa5;">Info</span>
                            @endif
                        @elseif ($n->belumDitanggapi())
                            <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Perlu konfirmasi</span>
                        @else
                            <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Sudah dikonfirmasi</span>
                        @endif
                    </div>
                    <div class="text-muted small">{{ $n->keterangan }}</div>
                    <div class="text-muted small">
                        Kelas {{ $n->kelas }} &middot; {{ $n->tgl_warning?->translatedFormat('d F Y') }}
                        @if ($n->pelapor) &middot; oleh {{ $n->pelapor->nama }} @endif
                    </div>

                    @if ($butuhKonfirmasi)
                        <form method="POST" action="{{ route('notifikasi.konfirmasi', $n) }}" class="mt-2 d-flex gap-2">
                            @csrf
                            <input type="text" name="alasan" class="form-control form-control-sm" placeholder="Tulis alasan Anda di sini..." required>
                            <button type="submit" class="btn btn-sm btn-primary text-nowrap">Kirim Alasan</button>
                        </form>
                    @elseif ($n->kategori === 'Kelas Kosong')
                        <div class="mt-1 small"><strong>Alasan:</strong> {{ $n->aksi }}</div>
                    @endif
                </div>
            </div>
        @endforeach
    @endif
</div>
@endsection
