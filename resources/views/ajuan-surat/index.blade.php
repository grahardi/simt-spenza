@extends('layouts.app')

@section('title', 'Ajuan Surat')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-signature fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Ajuan Surat - {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route('ajuan-surat.sppd.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Ajukan SPPD
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada ajuan surat.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal Ajuan</th><th>Jenis</th><th>Judul/Perihal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $a)
                    <tr>
                        <td>{{ $a->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->labelJenis() }}</td>
                        <td>{{ $a->data['tema'] ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $a->status === 'selesai' ? 'bg-success' : ($a->status === 'diproses' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $a->labelStatus() }}
                            </span>
                            @if ($a->status === 'selesai' && $a->jenis_surat === 'sppd')
                                <br>
                                <span class="badge {{ $a->status_bayar === 'sudah' ? 'bg-success' : 'bg-secondary' }} mt-1">
                                    {{ $a->status_bayar === 'sudah' ? 'Transport Terbayar' : 'Transport Belum Dibayar' }}
                                </span>
                                @if ($a->foto_bukti_perjalanan)
                                    <a href="{{ Storage::url($a->foto_bukti_perjalanan) }}" target="_blank" class="small d-block mt-1">
                                        <i class="fas fa-image me-1"></i> Lihat bukti foto
                                    </a>
                                @endif
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('ajuan-surat.sppd.edit', $a) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            @if ($a->file_pdf)
                                <a href="{{ Storage::url($a->file_pdf) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-word me-1"></i> Unduh Surat (Word)
                                </a>
                            @endif
                            @if ($a->status === 'selesai' && $a->jenis_surat === 'sppd')
                                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalBukti{{ $a->id }}">
                                    <i class="fas fa-camera me-1"></i> Bukti Perjalanan
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-dark" data-bs-toggle="modal" data-bs-target="#modalBayar{{ $a->id }}">
                                    <i class="fas fa-money-bill-wave me-1"></i> Status Bayar
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

{{ $daftar->onEachSide(1)->links() }}

{{-- Modal HARUS di luar <table>/<tbody> - taruh di dalam <tbody> itu HTML tidak valid dan bikin modal gagal muncul --}}
@foreach ($daftar as $a)
    @if ($a->status === 'selesai' && $a->jenis_surat === 'sppd')
        <div class="modal fade" id="modalBukti{{ $a->id }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Bukti Foto Perjalanan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        @if ($a->foto_bukti_perjalanan)
                            <a href="{{ Storage::url($a->foto_bukti_perjalanan) }}" target="_blank">
                                <img src="{{ Storage::url($a->foto_bukti_perjalanan) }}" class="img-fluid rounded border mb-3" style="max-height:260px;">
                            </a>
                        @else
                            <p class="text-muted small">Belum ada bukti foto perjalanan.</p>
                        @endif
                        <form method="POST" action="{{ route('surat-tu.upload-bukti', $a) }}" enctype="multipart/form-data" class="d-flex gap-2">
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

        <div class="modal fade" id="modalBayar{{ $a->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('surat-tu.tandai-bayar', $a) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Status Biaya Transport</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="mb-3">
                            Status sekarang:
                            @if ($a->status_bayar === 'sudah')
                                <span class="badge bg-success">Terbayar</span>
                            @else
                                <span class="badge bg-secondary">Belum Dibayar</span>
                            @endif
                        </p>
                        <label class="form-label">Nominal (Rp)</label>
                        <input type="number" name="nominal_transport" class="form-control" value="{{ $a->nominal_transport }}" min="0" step="1000" placeholder="contoh: 150000">
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
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
