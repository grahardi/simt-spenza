@extends('layouts.app')

@section('title', 'Ajuan Manual - Pilih Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-user-clock me-2"></i>Ajuan Manual - Pilih Guru</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ $cari }}" autofocus>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
        <div class="col-md-2">
            <a href="{{ route('absen-guru.index') }}" class="btn btn-outline-secondary w-100">Batal</a>
        </div>
    </form>
</div>

@if ($daftarGuru !== null)
    <div class="p-4 bg-white rounded shadow">
        @if ($daftarGuru->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Guru dengan kata kunci "{{ $cari }}" tidak ditemukan.
            </div>
        @else
            <div class="list-group">
                @foreach ($daftarGuru as $g)
                    <a href="{{ route('absen-guru.form-ajuan', $g) }}" class="list-group-item list-group-item-action">
                        {{ $g->nama }}
                    </a>
                @endforeach
            </div>
        @endif
    </div>
@endif
@endsection
