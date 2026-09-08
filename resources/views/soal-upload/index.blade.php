@extends('layouts.app')

@section('title', 'Kelola Soal')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-alt fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">{{ $semua ? 'Kelola Soal' : 'Soal yang Saya Upload' }}</h1>
    </div>
    @if ($semua && $daftar->isNotEmpty())
        <a href="{{ route('soal-upload.download-semua') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
            <i class="fas fa-download me-1"></i> Download Semua (ZIP)
        </a>
    @endif
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if (session('status_gagal'))
    <div class="alert alert-danger">{{ session('status_gagal') }}</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada soal yang terupload.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Kelas</th><th>Mapel</th>
                    @if ($semua)<th>Guru</th>@endif
                    <th>Terakhir Diupload</th><th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftar as $s)
                    <tr>
                        <td>{{ $s->kelas }}</td>
                        <td>{{ $s->mapel }}</td>
                        @if ($semua)<td>{{ $s->guru->nama ?? '-' }}</td>@endif
                        <td>{{ $s->updated_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ Storage::url($s->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
