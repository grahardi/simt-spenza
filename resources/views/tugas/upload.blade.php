@extends('layouts.app')

@section('title', ($tugas ? 'Tugas' : 'Upload Tugas') . ' - ' . $kelas)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-clipboard-list me-2"></i>{{ $tugas ? 'Tugas' : 'Upload Tugas' }} - {{ $guru->nama }} - Kelas {{ $kelas }}
    </h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:520px;">
    @if ($tugas)
        <p class="text-muted small">Tugas untuk kelas ini hari ini sudah pernah diupload - masih bisa diedit/diganti di bawah ini.</p>
    @endif

    <form method="POST" action="{{ route('tugas.simpan', [$guru, $kelas]) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Tugas</label>
            <input type="text" name="tugas" class="form-control" value="{{ old('tugas', $tugas->tugas ?? '') }}" placeholder="contoh: Kerjakan LKS hal. 20-22" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="2">{{ old('keterangan', $tugas->keterangan ?? '') }}</textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto/Lampiran (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="form-control">
            @if ($tugas && $tugas->gambar)
                <small class="text-muted d-block mt-1">
                    File saat ini: <a href="{{ Storage::url($tugas->gambar) }}" target="_blank">lihat file</a> (upload baru untuk mengganti)
                </small>
            @endif
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload me-1"></i> {{ $tugas ? 'Simpan Perubahan' : 'Upload' }}
            </button>
            <a href="{{ route('jadwal.guru', $guru) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
