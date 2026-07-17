@extends('layouts.app')

@section('title', 'Booking Ruang')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-door-open me-2"></i>Booking Ruang Serbaguna</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    <p class="text-muted small mb-3">
        Tanggal <strong>{{ \Carbon\Carbon::parse($tanggal)->translatedFormat('d F Y') }}</strong>, Jam ke-<strong>{{ $jam }}</strong>
    </p>
    <form method="POST" action="{{ route('smart.simpan', [$tanggal, $jam]) }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Keperluan (opsional)</label>
            <input type="text" name="keterangan" class="form-control" placeholder="contoh: Pelajaran, Rapat, Ujian">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-check me-1"></i> Booking</button>
            <a href="{{ route('smart.kalender') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
