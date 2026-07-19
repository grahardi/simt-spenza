@extends('layouts.app')

@section('title', 'Siswa Sakit')

@section('content')
@include('partials.menu-uks')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-briefcase-medical fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Siswa Sakit</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama atau nomor induk siswa..." value="{{ $cari }}" autofocus>
        </div>
        <div class="col-md-4">
            <select name="kelas" class="form-select">
                <option value="">Semua kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected($kelasFilter === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

@if ($siswa !== null)
    <div class="bg-white rounded shadow overflow-hidden">
        @if ($siswa->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Siswa tidak ditemukan.
            </div>
        @else
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr><th>No. Induk</th><th>Nama</th><th>Kelas</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr>
                            <td>{{ $s->id_member }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td class="text-end">
                                @if ($s->sedangDiUks)
                                    <span class="badge bg-warning text-dark">Sedang di UKS</span>
                                @else
                                    <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modalSakit{{ $s->id_member }}">
                                        <i class="fas fa-thermometer me-1"></i> Sakit
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    @foreach ($siswa as $s)
        @if (! $s->sedangDiUks)
            <div class="modal fade" id="modalSakit{{ $s->id_member }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('uks.simpan-sakit', $s) }}" class="modal-content">
                        @csrf
                        <div class="modal-header">
                            <h5 class="modal-title">Catat Sakit - {{ $s->nama_lengkap }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Keterangan Sakit (opsional)</label>
                            <input type="text" name="keterangan_sakit" class="form-control" placeholder="contoh: pusing, demam, sakit perut">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-danger">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
