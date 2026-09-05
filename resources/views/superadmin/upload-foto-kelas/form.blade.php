@extends('layouts.adminlte')

@section('title', 'Upload Foto Kelas')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Upload Foto Kelas</h3></div>
    <div class="card-body">
        @if (session('status_gagal'))
            <div class="alert alert-danger">{{ session('status_gagal') }}</div>
        @endif

        <div class="alert alert-info">
            Pilih kelas, lalu upload beberapa foto sekaligus. Sistem akan mencocokkan tiap foto ke nama siswa
            berdasarkan <strong>nama file</strong> (usahakan nama file = nama siswa, contoh: "Budi Santoso.jpg").
            Setelah upload, akan ada halaman konfirmasi sebelum benar-benar disimpan.
        </div>
        <div class="alert alert-warning">
            <i class="fas fa-info-circle me-1"></i> Folder foto per tingkat: Kelas 7 &rarr; <code>foto2026</code>,
            Kelas 8 &rarr; <code>foto2025</code>, Kelas 9 &rarr; <code>foto2024</code>.
            Untuk saat ini <strong>fokus di Kelas 7</strong> saja (Kelas 8 &amp; 9 sudah ada fotonya).
        </div>

        <form method="POST" action="{{ route('superadmin.upload-foto-kelas.unggah') }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label>Kelas</label>
                <select name="kelas" class="form-control" required>
                    @foreach ($daftarKelas as $k)
                        <option value="{{ $k }}" @selected(str_starts_with($k, '7'))>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Foto (boleh pilih banyak sekaligus)</label>
                <input type="file" name="foto[]" accept="image/*" class="form-control" multiple required>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload &amp; Cocokkan</button>
        </form>
    </div>
</div>
@endsection
