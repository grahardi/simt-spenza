@extends('layouts.app')

@section('title', 'Import Data Kartu Ujian')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-excel me-2"></i>Import Data Kartu Ujian</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    <p class="text-muted small">
        Format kolom: <strong>Nomor Induk, Password, Ruang, No Kursi</strong>. Baris pertama dianggap header (dilewati).
        Nomor Induk dicocokkan ke Data Siswa - kalau sudah pernah diimport sebelumnya, datanya akan diperbarui (bukan dobel).
    </p>
    <a href="{{ route('kartu-ujian.template-excel') }}" class="btn btn-outline-secondary btn-sm mb-3">
        <i class="fas fa-download me-1"></i> Download Template Excel
    </a>

    <form method="POST" action="{{ route('kartu-ujian.proses-import') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <input type="file" name="file_excel" accept=".xlsx,.xls" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Import</button>
        <a href="{{ route('kartu-ujian.index') }}" class="btn btn-outline-secondary">Kembali</a>
    </form>
</div>
@endsection
