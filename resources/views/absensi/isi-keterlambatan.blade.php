@extends('layouts.app')

@section('title', 'Isi Keterlambatan')

@section('content')
@include('partials.menu-absensi')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-clock fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Isi Keterlambatan</h1>
    </div>
    <a href="{{ route('absensi.isi') }}" class="btn btn-light btn-sm mt-2 mt-md-0 me-2">
        <i class="fas fa-search me-1"></i> Isi Absensi
    </a>
    <a href="{{ route('absensi.telat.list') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-list me-1"></i> Data Terlambat
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama atau nomor induk siswa..." value="{{ $cari }}" autofocus>
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
                <div class="siswa-row {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="siswa-info d-flex align-items-center gap-2">
                        @if ($s->foto_url)
                            <img src="{{ $s->foto_url }}" alt="" class="foto-siswa-kecil">
                        @else
                            <span class="foto-siswa-kecil foto-siswa-kosong">{{ $s->initials() }}</span>
                        @endif
                        <div>
                            <h6 class="mb-0 text-uppercase">
                                <span class="text-primary">{{ $s->id_member }}</span> - {{ $s->nama_lengkap }}
                                <span class="text-muted normal-case" style="font-size:12px; text-transform:none;">&middot; Kelas {{ $s->kelas }}</span>
                            </h6>

                        @if ($s->telatHariIni)
                            <div class="mt-1">
                                <span class="badge-status badge-t">
                                    <i class="fas fa-check-circle me-1"></i> Sudah tercatat terlambat hari ini
                                </span>
                            </div>
                        @elseif ($s->absenHariIni)
                            <div class="mt-1">
                                <span class="badge-status badge-{{ $s->absenHariIni->keterangan }}">
                                    Sudah terabsen {{ strtolower($s->absenHariIni->labelKeterangan()) }} hari ini - tidak bisa ditandai terlambat juga
                                </span>
                            </div>
                        @endif
                        </div>
                    </div>

                    @if (! $s->telatHariIni && ! $s->absenHariIni)
                        <div class="siswa-aksi">
                            <form method="POST" action="{{ route('absensi.telat', $s) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn-absen btn-absen-telat">
                                    <i class="fas fa-clock me-1"></i> Tandai Terlambat
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endforeach
        @endif
    </div>
@endif
@endsection
