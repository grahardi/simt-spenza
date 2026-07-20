@extends('layouts.app')

@section('title', 'Pendampingan')

@section('content')
@include('partials.menu-wali')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-hands-helping fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Pendampingan - {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route('pendampingan.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Pendampingan
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada catatan pendampingan.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal/Waktu</th><th>Kategori</th><th>Judul Kegiatan</th><th>Peserta</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $p)
                    <tr>
                        <td>{{ $p->tanggal_waktu->translatedFormat('d M Y, H:i') }}</td>
                        <td><span class="badge bg-secondary">{{ $p->kategori }}</span></td>
                        <td>{{ $p->judul }}</td>
                        <td>{{ $p->peserta_count }} siswa</td>
                        <td class="text-end">
                            @if ($p->foto)
                                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFoto{{ $p->id }}">
                                    <i class="fas fa-image"></i>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

{{ $daftar->onEachSide(1)->links() }}

@foreach ($daftar as $p)
    @if ($p->foto)
        <div class="modal fade" id="modalFoto{{ $p->id }}" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ $p->judul }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center">
                        <img src="{{ Storage::url($p->foto) }}" alt="Foto kegiatan" class="img-fluid rounded">
                        @if ($p->deskripsi)
                            <p class="text-start mt-3 mb-0">{{ $p->deskripsi }}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
