@extends('layouts.app')

@section('title', 'Pilih Guru - Jadwal')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal - Pilih Guru</h1>
    </div>
    <a href="{{ route('jadwal.index') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ $cari }}" autofocus>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

@if ($guru !== null)
    <div class="p-4 bg-white rounded shadow">
        @if ($guru->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Guru dengan kata kunci "{{ $cari }}" tidak ditemukan.
            </div>
        @else
            <div class="list-group">
                @foreach ($guru as $g)
                    <a href="{{ route('jadwal.guru', $g) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                        <span>{{ $g->nama }}</span>
                        <span class="text-muted small">{{ $g->jabatan }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endif
@endsection
