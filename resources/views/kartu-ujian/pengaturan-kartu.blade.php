@extends('layouts.app')

@section('title', 'Pengaturan Kartu Ujian')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-cogs me-2"></i>Pengaturan Kartu &amp; Meja Ujian</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    <form method="POST" action="{{ route('kartu-ujian.simpan-pengaturan-kartu') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Judul di Kartu/Meja</label>
            <input type="text" name="judul" class="form-control" value="{{ $pengaturan->judul }}" required>
            <small class="text-muted">Contoh: "Kartu Peserta Sumatif Akhir Jenjang"</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal di Kartu/Meja</label>
            <input type="date" name="tanggal" class="form-control" value="{{ $pengaturan->tanggal->format('Y-m-d') }}" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
        <a href="{{ route('kartu-ujian.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </form>
</div>
@endsection
