@extends('layouts.app')

@section('title', ($tugas ? 'Tugas' : ($bisaEdit ? 'Upload Tugas' : 'Belum Ada Tugas')) . ' - ' . $kelas)

@php
    $isPdf = fn ($path) => $path && str_ends_with(strtolower($path), '.pdf');
@endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-clipboard-list me-2"></i>{{ $tugas ? 'Tugas' : ($bisaEdit ? 'Upload Tugas' : 'Belum Ada Tugas') }} - {{ $guru->nama }} - Kelas {{ $kelas }}
        <span class="fs-6 fw-normal">({{ $tanggal->translatedFormat('d F Y') }})</span>
    </h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    @if (!$bisaEdit)
        {{-- Piket/TU/dll cuma boleh LIHAT/DOWNLOAD tugas yang sudah diupload guru, tidak bisa upload/edit --}}
        @if ($tugas)
            <table class="table table-sm mb-3">
                <tr><th width="140">Tugas</th><td>{{ $tugas->tugas }}</td></tr>
                @if ($tugas->keterangan)
                    <tr><th>Keterangan</th><td>{{ $tugas->keterangan }}</td></tr>
                @endif
            </table>
            @if ($tugas->gambar)
                <label class="form-label d-block">Lampiran</label>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalLampiran">
                    <i class="fas fa-{{ $isPdf($tugas->gambar) ? 'file-pdf' : 'image' }} me-1"></i> Lihat Lampiran
                </button>
                <a href="{{ Storage::url($tugas->gambar) }}" target="_blank" download class="btn btn-outline-secondary">
                    <i class="fas fa-download me-1"></i> Download
                </a>
            @endif
        @else
            <div class="text-muted text-center py-4">
                <i class="far fa-clock me-1"></i> Guru belum mengupload tugas untuk kelas ini.
            </div>
        @endif

        <a href="{{ request('dari_piket') ? route('absen-guru.index') : route('jadwal.guru', $guru) }}" class="btn btn-outline-secondary mt-3 d-block">Kembali</a>
    @else
        @if ($tugas)
            <p class="text-muted small">Tugas untuk kelas ini pada tanggal ini sudah pernah diupload - masih bisa diedit/diganti di bawah ini.</p>

            @if ($tugas->gambar)
                <div class="mb-3">
                    <label class="form-label d-block">Lampiran Saat Ini</label>
                    <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalLampiran">
                        <i class="fas fa-{{ $isPdf($tugas->gambar) ? 'file-pdf' : 'image' }} me-1"></i> Lihat Lampiran
                    </button>
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
                <input type="file" name="foto" accept="image/*,.doc,.docx,.pdf" class="form-control">
                <small class="text-muted">Gambar langsung tersimpan apa adanya. File Word (.doc/.docx) otomatis dikonversi jadi PDF.</small>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-upload me-1"></i> {{ $tugas ? 'Simpan Perubahan' : 'Upload' }}
                </button>
                <a href="{{ request('dari_piket') ? route('absen-guru.index') : (request('dari_ajuan_sendiri') ? route('ajuan-absen-guru.index', ['tanggal' => $tanggal->toDateString()]) : route('jadwal.guru', $guru)) }}" class="btn btn-outline-secondary">Kembali</a>
            </div>
        </form>
    @endif
</div>

@if ($tugas && $tugas->gambar)
    <div class="modal fade" id="modalLampiran" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Lampiran Tugas - Kelas {{ $kelas }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center p-0" style="min-height:70vh;">
                    @if ($isPdf($tugas->gambar))
                        <iframe src="{{ Storage::url($tugas->gambar) }}" style="width:100%;height:75vh;border:0;"></iframe>
                    @else
                        <img src="{{ Storage::url($tugas->gambar) }}" alt="Lampiran tugas" class="img-fluid" style="max-height:80vh;">
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="{{ Storage::url($tugas->gambar) }}" target="_blank" download class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-download me-1"></i> Download
                    </a>
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
