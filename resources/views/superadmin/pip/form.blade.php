@extends('layouts.adminlte')

@section('title', 'Import Data PIP')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Import Data PIP</h3></div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <div class="alert alert-info">
            Upload file Excel/XLS hasil download dari sistem PIP. Pencocokan siswa dilakukan lewat <strong>NISN</strong>.
            Kalau ada NISN yang tidak ketemu, akan muncul halaman konfirmasi kemiripan nama sebelum data itu ikut disimpan.
        </div>

        <form method="POST" action="{{ route('superadmin.pip.unggah') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <input type="file" name="file_excel" accept=".xlsx,.xls" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload &amp; Proses</button>
        </form>
    </div>
</div>
@endsection
