@extends('layouts.app')

@section('title', 'Detail Ajuan Surat')

@section('content')
@include('persuratan._menu')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-signature me-2"></i>Detail Ajuan - {{ $ajuan->guru->nama ?? '-' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:700px;">
    <table class="table table-sm">
        <tr><td width="180">Jenis Surat</td><td>: {{ $ajuan->labelJenis() }}</td></tr>
        <tr><td>Guru Pengaju</td><td>: {{ $ajuan->guru->nama ?? '-' }}</td></tr>
        <tr><td>NIP</td><td>: {{ $ajuan->guru->nip ?? '-' }}</td></tr>
        <tr><td>Tanggal Ajuan</td><td>: {{ $ajuan->created_at->translatedFormat('d F Y, H:i') }}</td></tr>
        <tr><td>Status</td><td>: <span class="badge {{ $ajuan->status === 'selesai' ? 'bg-success' : 'bg-secondary' }}">{{ $ajuan->labelStatus() }}</span></td></tr>
    </table>

    <hr>
    <h6 class="mb-3">Detail Isian</h6>
    <table class="table table-sm table-bordered">
        <tr><td width="200">Dasar Penugasan</td><td>{{ $ajuan->data['dasar'] ?? '-' }}</td></tr>
        <tr><td>Hari</td><td>{{ $ajuan->data['hari'] ?? '-' }}</td></tr>
        <tr><td>Tanggal Berangkat</td><td>{{ $ajuan->data['tanggal'] ?? '-' }}</td></tr>
        <tr><td>Tanggal Kembali</td><td>{{ $ajuan->data['tanggal_selesai'] ?? '-' }}</td></tr>
        <tr><td>Jam</td><td>{{ $ajuan->data['jam_mulai'] ?? '-' }} s.d. {{ $ajuan->data['jam_selesai'] ?? 'selesai' }}</td></tr>
        <tr><td>Total Hari</td><td>{{ $ajuan->data['total_hari'] ?? 1 }}</td></tr>
        <tr><td>Tempat Kegiatan</td><td>{{ $ajuan->data['tempat'] ?? '-' }}</td></tr>
        <tr><td>Tempat Tujuan</td><td>{{ $ajuan->data['tempat_tujuan'] ?? '-' }}</td></tr>
        <tr><td>Tema/Perihal</td><td>{{ $ajuan->data['tema'] ?? '-' }}</td></tr>
        <tr><td>Maksud</td><td>{{ $ajuan->data['maksud'] ?? '-' }}</td></tr>
    </table>

    @if ($ajuan->status === 'selesai')
        <div class="alert alert-success">
            Surat sudah dibuat dengan nomor <strong>{{ $ajuan->nomor_surat }}</strong>.
            <a href="{{ Storage::url($ajuan->file_pdf) }}" target="_blank" class="btn btn-sm btn-outline-danger ms-2">
                <i class="fas fa-file-pdf me-1"></i> Lihat PDF
            </a>
        </div>
    @else
        <form method="POST" action="{{ route('surat-tu.buat-surat', $ajuan) }}" class="mt-3">
            @csrf
            <label class="form-label">Nomor Surat</label>
            <div class="d-flex gap-2">
                <input type="text" name="nomor_surat" class="form-control" placeholder="contoh: 422/012/35.07.301.09.43/2026" required>
                <button type="submit" class="btn btn-primary text-nowrap">
                    <i class="fas fa-file-pdf me-1"></i> Buat Surat (PDF)
                </button>
            </div>
        </form>
    @endif

    <a href="{{ route('surat-tu.index') }}" class="btn btn-outline-secondary mt-3">Kembali</a>
</div>
@endsection
