@extends('layouts.app')

@section('title', 'Data Kebersihan Kelas')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-broom fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Laporan Kebersihan</h1>
    </div>
    <a href="{{ route('kebersihan.kelas-grid') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Laporan
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($laporan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada laporan kebersihan pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Pelapor</th>
                        <th>Jam</th>
                        <th>Foto Kondisi</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($laporan as $i => $l)
                        <tr>
                            <td>{{ $laporan->firstItem() + $i }}</td>
                            <td>{{ $l->kelas }}</td>
                            <td>{{ $l->guru->nama ?? '-' }}</td>
                            <td>{{ $l->jam }}</td>
                            <td>
                                <a href="{{ Storage::url($l->gambar) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-image"></i> Lihat
                                </a>
                            </td>
                            <td>
                                @if ($l->sudahDitindak())
                                    <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Selesai</span>
                                @else
                                    <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Belum</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if ($l->sudahDitindak())
                                    <a href="{{ Storage::url($l->gambaraksi) }}" target="_blank" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-check me-1"></i> Lihat Aksi
                                    </a>
                                @else
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTindak{{ $l->id }}">
                                        <i class="fas fa-broom me-1"></i> Tindak Lanjuti
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $laporan->onEachSide(1)->links() }}
    @endif
</div>

@foreach ($laporan as $l)
    @if (!$l->sudahDitindak())
        <div class="modal fade" id="modalTindak{{ $l->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('kebersihan.tindak', $l) }}" enctype="multipart/form-data" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tindak Lanjut - Kelas {{ $l->kelas }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Upload foto bukti kelas sudah dibersihkan.</p>
                        <input type="file" name="foto_aksi" accept="image/*" class="form-control" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
