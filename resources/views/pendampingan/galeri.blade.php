@extends('layouts.app')

@section('title', 'Galeri Wali')

@section('content')
@include('partials.menu-wali')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-images me-2"></i>Galeri Wali</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($galeri->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada kegiatan pendampingan (umum, berfoto) pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="row g-3">
            @foreach ($galeri as $p)
                <div class="col-6 col-md-3">
                    <button type="button" class="btn p-0 border-0 bg-transparent w-100 text-start" data-bs-toggle="modal" data-bs-target="#modalDetail{{ $p->id }}">
                        <div class="border rounded p-2">
                            <img src="{{ Storage::url($p->foto) }}" class="img-fluid rounded mb-2" style="aspect-ratio:1;object-fit:cover;width:100%;">
                            <div class="fw-semibold small">{{ $p->judul }}</div>
                            <div class="text-muted small">
                                {{ $p->guru->nama ?? '-' }} &middot; {{ $p->tanggal_waktu->format('H:i') }}
                            </div>
                        </div>
                    </button>
                </div>
            @endforeach
        </div>

        {{ $galeri->onEachSide(1)->links() }}
    @endif
</div>

@foreach ($galeri as $p)
    <div class="modal fade" id="modalDetail{{ $p->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $p->judul }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <img src="{{ Storage::url($p->foto) }}" alt="Foto kegiatan" class="img-fluid rounded mb-3">
                    <table class="table table-sm mb-0">
                        <tr><td width="120">Kategori</td><td>: {{ $p->kategori }}</td></tr>
                        <tr><td>Guru Wali</td><td>: {{ $p->guru->nama ?? '-' }}</td></tr>
                        <tr><td>Tanggal</td><td>: {{ $p->tanggal_waktu->translatedFormat('d F Y, H:i') }}</td></tr>
                        <tr><td>Peserta</td><td>: {{ $p->peserta->pluck('nama_lengkap')->implode(', ') ?: '-' }}</td></tr>
                        @if ($p->deskripsi)
                            <tr><td>Deskripsi</td><td>: {{ $p->deskripsi }}</td></tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
