@extends('layouts.app')

@section('title', 'Isi Absensi')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-search fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Pengisian Absensi</h1>
    </div>
    <a href="{{ route('absensi.telat.list') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-clock me-1"></i> Data Terlambat
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama atau NIS siswa..." value="{{ $cari }}" autofocus>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

@if ($siswa !== null)
    <div class="p-4 bg-white rounded shadow">
        @if ($siswa->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Siswa dengan kata kunci "{{ $cari }}" tidak ditemukan.
            </div>
        @else
            @foreach ($siswa as $s)
                <div class="d-flex flex-column flex-md-row align-items-md-center py-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="me-md-3 mb-2 mb-md-0">
                        <h6 class="mb-0 text-uppercase">
                            <span class="text-primary">{{ $s->id_member }}</span> - {{ $s->nama_lengkap }}
                        </h6>
                        <small class="text-muted">Kelas {{ $s->kelas }}</small>
                    </div>
                    <div class="ms-md-auto d-flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('absensi.tandai', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="s">
                            <button type="submit" class="btn btn-warning btn-sm"><i class="fas fa-thermometer me-1"></i> Sakit</button>
                        </form>
                        <form method="POST" action="{{ route('absensi.tandai', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="i">
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-envelope me-1"></i> Ijin</button>
                        </form>
                        <form method="POST" action="{{ route('absensi.tandai', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="a">
                            <button type="submit" class="btn btn-danger btn-sm"><i class="fas fa-times me-1"></i> Alfa</button>
                        </form>
                        <form method="POST" action="{{ route('absensi.tandai', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="d">
                            <button type="submit" class="btn btn-info btn-sm text-white"><i class="fas fa-bus me-1"></i> Dispensasi</button>
                        </form>
                        <form method="POST" action="{{ route('absensi.telat', $s) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-secondary btn-sm"><i class="fas fa-clock me-1"></i> Terlambat</button>
                        </form>
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endif
@endsection
