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

    @if ($ajuan->file_pendukung)
        <div class="mb-3">
            <label class="form-label d-block">Berkas Pendukung</label>
            @if (str_ends_with($ajuan->file_pendukung, '.pdf'))
                <a href="{{ Storage::url($ajuan->file_pendukung) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                    <i class="fas fa-file-pdf me-1"></i> Lihat PDF Pendukung
                </a>
            @else
                <a href="{{ Storage::url($ajuan->file_pendukung) }}" target="_blank">
                    <img src="{{ Storage::url($ajuan->file_pendukung) }}" alt="Berkas pendukung" class="img-fluid rounded border" style="max-height:280px;">
                </a>
            @endif
        </div>
    @endif

    <hr>
    <h6 class="mb-3">Detail Isian</h6>
    <table class="table table-sm table-bordered">
        <tr><td width="200">Isian Surat Undangan</td><td>{{ $ajuan->data['isian_form'] ?? '-' }}</td></tr>
        <tr><td>Tanggal/Nomor Surat Undangan</td><td>{{ $ajuan->data['tanggal_dasar'] ?? '-' }} / {{ $ajuan->data['nomor_surat_dasar'] ?? '-' }}</td></tr>
        <tr><td>Hari, Tanggal Berangkat</td><td>{{ $ajuan->data['hari'] ?? '-' }}, {{ $ajuan->data['tanggal'] ?? '-' }}</td></tr>
        <tr><td>Tanggal Kembali</td><td>{{ $ajuan->data['tanggal_selesai'] ?? '-' }}</td></tr>
        <tr><td>Jam</td><td>{{ $ajuan->data['jam_mulai'] ?? '-' }} s.d. {{ $ajuan->data['jam_selesai'] ?? 'selesai' }}</td></tr>
        <tr><td>Total Hari</td><td>{{ $ajuan->data['total_hari'] ?? 1 }}</td></tr>
        <tr><td>Tempat Tujuan</td><td>{{ $ajuan->data['tempat_tujuan'] ?? '-' }}</td></tr>
        <tr><td>Tema/Perihal</td><td>{{ $ajuan->data['tema'] ?? '-' }}</td></tr>
    </table>

    @if ($ajuan->status === 'selesai')
        <div class="alert alert-success">
            Surat sudah dibuat dengan nomor <strong>{{ $ajuan->nomor_surat }}</strong> (format Word, siap diprint atau di-convert PDF manual).
            <a href="{{ Storage::url($ajuan->file_pdf) }}" class="btn btn-sm btn-outline-primary ms-2">
                <i class="fas fa-file-word me-1"></i> Unduh Surat (.docx)
            </a>
        </div>
    @else
        <a href="{{ route('ajuan-surat.sppd.edit', $ajuan) }}" class="btn btn-outline-secondary mt-2 mb-2">
            <i class="fas fa-edit me-1"></i> Edit Data Ajuan
        </a>
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
