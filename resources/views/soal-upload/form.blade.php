@extends('layouts.app')

@section('title', 'Upload Soal')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-upload me-2"></i>
        <h1 class="h5 pt-2 mb-0">Upload Soal</h1>
    </div>
    <a href="{{ route('soal-upload.milik-saya') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-list me-1"></i> Soal yang Sudah Saya Upload
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
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
    <form method="POST" action="{{ route('soal-upload.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas" class="form-select" required>
                <option value="7">Kelas 7</option>
                <option value="8">Kelas 8</option>
                <option value="9">Kelas 9</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            <select name="mapel" class="form-select" required>
                <option value="">- Pilih mapel -</option>
                @foreach ($daftarMapel as $label)
                    <option value="{{ $label }}">{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Format Pengumpulan</label>
            <select name="tipe" class="form-select" required>
                <option value="aplikasi" selected>Format Aplikasi (default)</option>
                <option value="cetak">Format Cetak</option>
            </select>
            <small class="text-muted">Format Cetak masih baru, belum aktif digunakan - kalau tidak yakin, biarkan "Format Aplikasi".</small>
        </div>
        <div class="mb-3">
            <label class="form-label">File Soal (.docx)</label>
            <input type="file" name="file_soal" accept=".docx" class="form-control" required>
            <small class="text-muted">Kalau sudah pernah upload untuk kelas+mapel yang sama, file lama otomatis dipindah ke arsip (tidak hilang).</small>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
    </form>
</div>
@endsection
