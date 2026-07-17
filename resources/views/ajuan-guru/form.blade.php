@extends('layouts.app')

@section('title', 'Ajukan Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-map-marker-alt me-2"></i>Ajukan Guru - Lapor Kelas Kosong</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info small">
    <i class="fas fa-info-circle me-1"></i>
    Laporan ini akan masuk ke notifikasi guru yang bersangkutan dan <strong>wajib dikonfirmasi alasannya</strong> oleh guru tersebut.
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
            @foreach ($guru as $g)
                <div class="border rounded p-3 mb-2">
                    <div class="fw-semibold mb-2">{{ $g->nama }} <span class="text-muted small">({{ $g->jabatan }})</span></div>
                    <form method="POST" action="{{ route('ajuan-guru.simpan', $g) }}" class="row g-2">
                        @csrf
                        <div class="col-md-3">
                            <input type="text" name="kelas" class="form-control" placeholder="Kelas, contoh: 7 - A" required>
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="jam" class="form-control" placeholder="Jam ke-">
                        </div>
                        <div class="col-md-5">
                            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan, contoh: tidak ada yang mengajar" required>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary w-100">Kirim</button>
                        </div>
                    </form>
                </div>
            @endforeach
        @endif
    </div>
@endif
@endsection
