@extends('layouts.app')

@section('title', 'Galeri - ' . $agenda->judul)

@php
    $bisaUpload = auth('member')->user()->hasRole('kesiswaan') || auth('member')->user()->hasRole('admin_kegiatan');
@endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-images me-2"></i>Galeri - {{ $agenda->judul }}</h1>
    <p class="mb-0 small text-white-50">
        {{ $agenda->tanggal_mulai->translatedFormat('d F Y') }}
        @if ($agenda->tanggal_selesai && !$agenda->tanggal_selesai->isSameDay($agenda->tanggal_mulai))
            s/d {{ $agenda->tanggal_selesai->translatedFormat('d F Y') }}
        @endif
    </p>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if ($bisaUpload)
    <div class="p-3 bg-white rounded shadow mb-3">
        <form method="POST" action="{{ route('agenda.foto.store', $agenda) }}" enctype="multipart/form-data" class="d-flex flex-wrap align-items-center gap-2">
            @csrf
            <input type="file" name="foto[]" accept="image/*" class="form-control" style="max-width:360px;" multiple required>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload Foto</button>
        </form>
    </div>
@endif

<div class="p-3 bg-white rounded shadow">
    @if ($agenda->foto->isEmpty())
        <div class="text-muted text-center py-5">
            <i class="far fa-images fa-2x mb-2 d-block"></i> Belum ada foto untuk kegiatan ini.
        </div>
    @else
        <div class="galeri-grid">
            @foreach ($agenda->foto as $i => $f)
                <div class="galeri-item" role="button" data-bs-toggle="modal" data-bs-target="#lightbox{{ $f->id }}">
                    <img src="{{ Storage::url($f->path) }}" alt="Foto kegiatan {{ $i + 1 }}" loading="lazy">
                </div>
            @endforeach
        </div>
    @endif
</div>

@foreach ($agenda->foto as $f)
    <div class="modal fade" id="lightbox{{ $f->id }}" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content bg-dark">
                <div class="modal-header border-0">
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0">
                    <img src="{{ Storage::url($f->path) }}" class="img-fluid" style="max-height:80vh;">
                </div>
                @if ($bisaUpload)
                    <div class="modal-footer border-0 justify-content-center">
                        <form method="POST" action="{{ route('agenda.foto.hapus', $f) }}" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-outline-light btn-sm"><i class="fas fa-trash me-1"></i> Hapus Foto</button>
                        </form>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endforeach

<style>
.galeri-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 4px;
}
.galeri-item {
    position: relative;
    aspect-ratio: 1 / 1;
    overflow: hidden;
    cursor: pointer;
    border-radius: 4px;
}
.galeri-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .2s;
}
.galeri-item:hover img {
    transform: scale(1.05);
}
</style>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
