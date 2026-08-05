@extends('layouts.app')

@section('title', 'Ajuan Manual - ' . $guru->nama)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-user-clock me-2"></i>Ajuan Manual - {{ $guru->nama }}</h1>
</div>

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    <form method="POST" action="{{ route('absen-guru.simpan-ajuan', $guru) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal', now('Asia/Jakarta')->toDateString()) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
                <option value="s">Sakit</option>
                <option value="i">Ijin</option>
                <option value="d">Dispensasi</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan (opsional)</label>
            <input type="text" name="keterangan" class="form-control" placeholder="contoh: demam, ada urusan keluarga">
        </div>
        <div class="mb-3">
            <label class="form-label">Foto Surat (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="form-control">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Kirim Ajuan</button>
            <a href="{{ route('absen-guru.pilih-guru') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
