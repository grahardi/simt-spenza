@extends('layouts.app')

@section('title', $ajuan->data['tema'] ?? $ajuan->data['kegiatan'] ?? 'Detail Ajuan Surat')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-file-signature me-2"></i>{{ $ajuan->data['tema'] ?? $ajuan->data['kegiatan'] ?? '-' }}
    </h1>
    <p class="mb-0 small text-white-50">{{ $ajuan->labelJenis() }} &middot; Diajukan {{ $ajuan->created_at->translatedFormat('d F Y') }}</p>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow mb-3" style="max-width:640px;">
    <table class="table table-sm mb-0">
        <tr><td width="180">Status</td><td>
            <span class="badge {{ $ajuan->status === 'selesai' ? 'bg-success' : ($ajuan->status === 'diproses' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                {{ $ajuan->labelStatus() }}
            </span>
        </td></tr>
        @if ($ajuan->status === 'selesai' && $ajuan->nomor_surat)
            <tr><td>Nomor Surat</td><td>{{ $ajuan->nomor_surat }}</td></tr>
        @endif
        @if ($ajuan->status === 'selesai' && $ajuan->jenis_surat === 'sppd')
            <tr><td>Status Bayar Transport</td><td>
                <span class="badge {{ $ajuan->status_bayar === 'sudah' ? 'bg-success' : 'bg-secondary' }}">
                    {{ $ajuan->status_bayar === 'sudah' ? 'Terbayar' : 'Belum Dibayar' }}
                </span>
                @if ($ajuan->status_bayar === 'sudah' && $ajuan->nominal_transport)
                    <span class="text-muted small ms-1">Rp {{ number_format($ajuan->nominal_transport, 0, ',', '.') }}</span>
                @endif
            </td></tr>
        @endif
    </table>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:640px;">
    <h6 class="mb-3">Aksi</h6>
    <div class="d-flex flex-wrap gap-2">
        <a href="{{ route($ajuan->jenis_surat === 'surat_permohonan' ? 'ajuan-surat.permohonan.edit' : 'ajuan-surat.sppd.edit', $ajuan) }}" class="btn btn-outline-secondary">
            <i class="fas fa-edit me-1"></i> Edit Data Ajuan
        </a>

        @if ($ajuan->file_pendukung)
            <a href="{{ Storage::url($ajuan->file_pendukung) }}" target="_blank" class="btn btn-outline-info">
                <i class="fas fa-envelope-open-text me-1"></i> Lihat Undangan
            </a>
        @endif

        @if ($ajuan->file_pdf)
            <a href="{{ Storage::url($ajuan->file_pdf) }}" class="btn btn-outline-primary">
                <i class="fas fa-file-word me-1"></i> Unduh Surat (Word)
            </a>
        @endif

        @if ($ajuan->status === 'selesai' && $ajuan->jenis_surat === 'sppd')
            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalBukti">
                <i class="fas fa-camera me-1"></i> Bukti Perjalanan
            </button>
            <button type="button" class="btn btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalBayar">
                <i class="fas fa-money-bill-wave me-1"></i> Status Bayar
            </button>
        @endif
    </div>

    <a href="{{ route('ajuan-surat.index') }}" class="btn btn-link mt-3 ps-0">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke List
    </a>
</div>

@if ($ajuan->status === 'selesai' && $ajuan->jenis_surat === 'sppd')
    <div class="modal fade" id="modalBukti" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Bukti Foto Perjalanan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($ajuan->foto_bukti_perjalanan)
                        <a href="{{ Storage::url($ajuan->foto_bukti_perjalanan) }}" target="_blank">
                            <img src="{{ Storage::url($ajuan->foto_bukti_perjalanan) }}" class="img-fluid rounded border mb-3" style="max-height:260px;">
                        </a>
                    @else
                        <p class="text-muted small">Belum ada bukti foto perjalanan.</p>
                    @endif
                    <form method="POST" action="{{ route('surat-tu.upload-bukti', $ajuan) }}" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="file" name="foto_bukti" accept="image/*" class="form-control" required>
                        <button type="submit" class="btn btn-primary text-nowrap">Upload</button>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalBayar" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('surat-tu.tandai-bayar', $ajuan) }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Status Biaya Transport</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-3">
                        Status sekarang:
                        @if ($ajuan->status_bayar === 'sudah')
                            <span class="badge bg-success">Terbayar</span>
                        @else
                            <span class="badge bg-secondary">Belum Dibayar</span>
                        @endif
                    </p>
                    <label class="form-label">Nominal (Rp)</label>
                    <input type="number" name="nominal_transport" class="form-control" value="{{ $ajuan->nominal_transport }}" min="0" step="1000" placeholder="contoh: 150000">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" name="status_bayar" value="belum" class="btn btn-outline-danger">Tandai Belum</button>
                    <button type="submit" name="status_bayar" value="sudah" class="btn btn-success">Bayar/Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
