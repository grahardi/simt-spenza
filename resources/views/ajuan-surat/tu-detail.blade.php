@extends('layouts.app')

@section('title', 'Detail Ajuan Surat')

@section('content')
@include('persuratan._menu')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-file-signature me-2"></i>Detail Ajuan -
        {{ $ajuan->jenis_surat === 'surat_permohonan' ? ($ajuan->data['kegiatan'] ?? '-') : ($ajuan->guru->nama ?? '-') }}
    </h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:700px;">
    <table class="table table-sm">
        <tr><td width="180">Jenis Surat</td><td>: {{ $ajuan->labelJenis() }}</td></tr>
        @if ($ajuan->jenis_surat === 'surat_permohonan')
            <tr><td>Ditujukan Kepada</td><td>: {{ $ajuan->data['tujuan'] ?? '-' }}</td></tr>
        @else
            <tr><td>Guru Pengaju</td><td>: {{ $ajuan->guru->nama ?? '-' }}</td></tr>
            <tr><td>NIP</td><td>: {{ $ajuan->guru->nip ?? '-' }}</td></tr>
        @endif
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
        @if ($ajuan->jenis_surat === 'surat_permohonan')
            <tr><td width="200">Alamat/Kota Tujuan</td><td>{{ $ajuan->data['alamat'] ?? '-' }}, {{ $ajuan->data['kota'] ?? '-' }}</td></tr>
            <tr><td>Kegiatan</td><td>{{ $ajuan->data['kegiatan'] ?? '-' }}</td></tr>
            <tr><td>Hari, Tanggal</td><td>{{ $ajuan->data['hari'] ?? '-' }}, {{ $ajuan->data['tanggal'] ?? '-' }}</td></tr>
            <tr><td>Waktu</td><td>{{ $ajuan->data['waktu'] ?? '-' }}</td></tr>
            <tr><td>Tempat</td><td>{{ $ajuan->data['tempat'] ?? '-' }}</td></tr>
            <tr><td>Permohonan/Tindakan</td><td>{{ $ajuan->data['tindakan'] ?? '-' }}</td></tr>
        @else
            <tr><td width="200">Isian Surat Undangan</td><td>{{ $ajuan->data['isian_form'] ?? '-' }}</td></tr>
            <tr><td>Tanggal/Nomor Surat Undangan</td><td>{{ $ajuan->data['tanggal_dasar'] ?? '-' }} / {{ $ajuan->data['nomor_surat_dasar'] ?? '-' }}</td></tr>
            <tr><td>Hari, Tanggal Berangkat</td><td>{{ $ajuan->data['hari'] ?? '-' }}, {{ $ajuan->data['tanggal'] ?? '-' }}</td></tr>
            <tr><td>Tanggal Kembali</td><td>{{ $ajuan->data['tanggal_selesai'] ?? '-' }}</td></tr>
            <tr><td>Jam</td><td>{{ $ajuan->data['jam_mulai'] ?? '-' }} s.d. {{ $ajuan->data['jam_selesai'] ?? 'selesai' }}</td></tr>
            <tr><td>Total Hari</td><td>{{ $ajuan->data['total_hari'] ?? 1 }}</td></tr>
            <tr><td>Tempat Tujuan</td><td>{{ $ajuan->data['tempat_tujuan'] ?? '-' }}</td></tr>
            <tr><td>Tema/Perihal</td><td>{{ $ajuan->data['tema'] ?? '-' }}</td></tr>
        @endif
    </table>

    @if ($ajuan->status === 'selesai')
        <div class="alert alert-success">
            Surat sudah dibuat dengan nomor <strong>{{ $ajuan->nomor_surat }}</strong> (format Word, siap diprint atau di-convert PDF manual).
            <a href="{{ Storage::url($ajuan->file_pdf) }}" class="btn btn-sm btn-outline-primary ms-2">
                <i class="fas fa-file-word me-1"></i> Unduh Surat (.docx)
            </a>
        </div>
    @endif

    <a href="{{ $ajuan->jenis_surat === 'surat_permohonan' ? route('ajuan-surat.permohonan.edit', $ajuan) : route('ajuan-surat.sppd.edit', $ajuan) }}" class="btn btn-outline-secondary mt-2 mb-2">
        <i class="fas fa-edit me-1"></i> Edit Data Ajuan
    </a>
    <form method="POST" action="{{ route('surat-tu.buat-surat', $ajuan) }}" class="mt-3">
        @csrf
        @php
            // Kalau pernah dibuat sebelumnya, isi ulang kode_umum & nomor_urut dari nomor lama
            $bagianLama = $ajuan->nomor_surat ? explode('/', $ajuan->nomor_surat) : [];
            $kodeUmumLama = $bagianLama[0] ?? '800';
            $nomorUrutLama = $bagianLama[1] ?? $nomorUrutBerikutnya;
        @endphp
        <label class="form-label">Nomor Surat</label>
        <div class="d-flex align-items-center gap-2 flex-wrap">
            <input type="text" name="kode_umum" class="form-control" style="max-width:100px" value="{{ $kodeUmumLama }}" placeholder="800" required>
            <span>/</span>
            <input type="number" name="nomor_urut" class="form-control" style="max-width:120px" value="{{ $nomorUrutLama }}" min="1" required>
            <span>/ {{ $kodeBaku }} / {{ now()->format('Y') }}</span>
            <button type="submit" class="btn btn-primary text-nowrap">
                <i class="fas fa-sync-alt me-1"></i> {{ $ajuan->status === 'selesai' ? 'Generate Ulang' : 'Buat Surat' }}
            </button>
        </div>
        <small class="text-muted d-block mt-1">Bagian belakang (kode baku &amp; tahun) terisi otomatis - cukup isi 2 kotak pertama.</small>
        @if ($ajuan->status === 'selesai')
            <small class="text-muted d-block">Kalau ada perubahan data, edit dulu di atas, baru klik Generate Ulang - file lama akan tertimpa yang baru.</small>
        @endif
    </form>

    <a href="{{ route('surat-tu.index') }}" class="btn btn-outline-secondary mt-3">Kembali</a>
</div>
@endsection
