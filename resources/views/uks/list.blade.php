@extends('layouts.app')

@section('title', 'Siswa di UKS')

@section('content')
@include('partials.menu-uks')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-bed fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Siswa di UKS</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px" value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada data UKS pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Waktu Masuk</th><th>Nama</th><th>Kelas</th><th>Keterangan Sakit</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $d)
                    <tr>
                        <td>{{ $d->waktu_masuk->format('H:i') }}</td>
                        <td>{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->siswa->kelas ?? '-' }}</td>
                        <td>{{ $d->keterangan_sakit ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $d->status === 'di_uks' ? 'bg-warning text-dark' : 'bg-success' }}">
                                {{ $d->labelStatus() }}
                            </span>
                            @if ($d->keterangan_penanganan)
                                <div class="small text-muted">{{ $d->keterangan_penanganan }}</div>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($d->status === 'di_uks')
                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalPenanganan{{ $d->id }}">
                                    <i class="fas fa-clipboard-check me-1"></i> Penanganan
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>

@foreach ($daftar as $d)
    @if ($d->status === 'di_uks')
        <div class="modal fade" id="modalPenanganan{{ $d->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('uks.penanganan', $d) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Penanganan - {{ $d->siswa->nama_lengkap ?? '-' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Pilih Penanganan</label>
                        <select name="status" class="form-select mb-3" required>
                            <option value="">- Pilih -</option>
                            <option value="kembali_kelas">Kembali ke Kelas</option>
                            <option value="pulang_dijemput">Pulang Dijemput</option>
                            <option value="puskesmas">Puskesmas</option>
                            <option value="lainnya">Lainnya</option>
                        </select>
                        <label class="form-label">Keterangan (opsional)</label>
                        <input type="text" name="keterangan_penanganan" class="form-control" placeholder="contoh: dijemput ibu jam 08.15">
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
