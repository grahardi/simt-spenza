@extends('layouts.app')

@section('title', 'Foto Siswa - ' . $judul)

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-images fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Foto Siswa - {{ $judul }}</h1>
    </div>
    <a href="{{ route('foto-siswa.pilih-kelas') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> {{ isset($cari) ? 'Kembali' : 'Ganti Kelas' }}
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa ditemukan.
        </div>
    @else
        <div class="row g-3">
            @foreach ($siswa as $s)
                <div class="col-6 col-md-3 col-lg-2">
                    <div class="border rounded-3 overflow-hidden h-100 d-flex flex-column">
                        <div style="aspect-ratio:3/4;background:#f4f5f7;" class="d-flex align-items-center justify-content-center overflow-hidden">
                            @if ($s->foto_url)
                                <img src="{{ $s->foto_url }}" class="w-100 h-100" style="object-fit:cover;">
                            @else
                                <span class="foto-siswa-placeholder">{{ $s->initials() }}</span>
                            @endif
                        </div>
                        <div class="p-2 flex-grow-1 d-flex flex-column">
                            <div class="fw-semibold small text-truncate" title="{{ $s->nama_lengkap }}">{{ $s->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size:11px;">{{ $s->id_member }} &middot; {{ $s->kelas }}</div>
                            <form method="POST" action="{{ route('foto-siswa.upload', $s) }}" enctype="multipart/form-data" class="mt-auto pt-2">
                                @csrf
                                <label class="btn btn-sm btn-outline-primary w-100 mb-0">
                                    <i class="fas fa-upload me-1"></i> {{ $s->foto_url ? 'Ganti' : 'Upload' }}
                                    <input type="file" name="foto" accept="image/jpeg" class="d-none" onchange="this.form.submit()">
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="text-muted small mt-3">Menampilkan {{ $siswa->count() }} siswa. Format foto: JPG/JPEG.</div>
    @endif
</div>
@endsection
