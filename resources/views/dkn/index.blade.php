@extends('layouts.app')

@section('title', 'DKN Kelas')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-list-ol me-2"></i>DKN Kelas {{ $kelas }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="p-4 bg-white rounded shadow mb-3" style="max-width:480px;">
    <h3 class="h6 mb-3">Upload Berkas DKN</h3>
    <form method="POST" action="{{ route('dkn.simpan') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Mata Pelajaran</label>
            @if ($daftarMapel->isEmpty())
                <input type="text" name="kode_mapel" class="form-control" placeholder="Kode mapel, contoh: MAT" required>
            @else
                <select name="kode_mapel" class="form-select" required>
                    @foreach ($daftarMapel as $m)
                        <option value="{{ $m->kode_mapel }}">{{ $m->nama_mapel }}</option>
                    @endforeach
                </select>
            @endif
        </div>
        <div class="mb-3">
            <label class="form-label">File DKN (PDF/Excel)</label>
            <input type="file" name="file" accept=".pdf,.xlsx,.xls" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h6 mb-3">Berkas Terupload</h3>
    @if ($daftarUpload->isEmpty())
        <div class="text-muted small">Belum ada berkas DKN yang diupload untuk kelas ini.</div>
    @else
        <table class="table table-striped">
            <thead><tr><th>Mapel</th><th>Terakhir Diupload</th><th>File</th></tr></thead>
            <tbody>
                @foreach ($daftarUpload as $d)
                    <tr>
                        <td>{{ $d->kode_mapel }}</td>
                        <td>{{ $d->uploaded_at?->translatedFormat('d F Y H:i') }}</td>
                        <td><a href="{{ Storage::url($d->nama_file) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-download"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
