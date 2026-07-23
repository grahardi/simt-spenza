@extends('layouts.app')

@section('title', ($tugas ? 'Tugas' : 'Upload Tugas') . ' - ' . $kelas)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-clipboard-list me-2"></i>{{ $tugas ? 'Tugas' : 'Upload Tugas' }} - {{ $guru->nama }} - Kelas {{ $kelas }}
        <span class="fs-6 fw-normal">({{ $tanggal->translatedFormat('d F Y') }})</span>
    </h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    @if ($tugas)
        <p class="text-muted small">Tugas untuk kelas ini pada tanggal ini sudah pernah diupload - masih bisa diedit/diganti di bawah ini.</p>

        @if ($tugas->gambar)
            <div class="mb-3">
                <label class="form-label d-block">Lampiran Saat Ini</label>
                {{-- Preview gambar langsung inline (bukan cuma link) --}}
                <img src="{{ Storage::url($tugas->gambar) }}" alt="Lampiran tugas" class="img-fluid rounded border" style="max-height:320px;">
            </div>
        @endif
    @endif

    <form method="POST" action="{{ route('tugas.simpan', [$guru, $kelas]) }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">
        @if (request('dari_ajuan_sendiri'))
            <input type="hidden" name="dari_ajuan_sendiri" value="1">
        @endif
        @if (request('dari_piket'))
            <input type="hidden" name="dari_piket" value="1">
        @endif
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
            <small class="text-muted">Upload foto baru untuk mengganti lampiran yang sudah ada.</small>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-upload me-1"></i> {{ $tugas ? 'Simpan Perubahan' : 'Upload' }}
            </button>
            <a href="{{ request('dari_piket') ? route('ajuan-absen-guru.piket.form', ['guru' => $guru, 'tanggal' => $tanggal->toDateString()]) : (request('dari_ajuan_sendiri') ? route('ajuan-absen-guru.index', ['tanggal' => $tanggal->toDateString()]) : route('jadwal.guru', $guru)) }}" class="btn btn-outline-secondary">Kembali</a>
        </div>
    </form>
</div>
@endsection
