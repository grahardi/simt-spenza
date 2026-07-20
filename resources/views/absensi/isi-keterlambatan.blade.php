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

@if ($errors->any())
    <div class="alert alert-danger">
        <strong>Gagal menyimpan:</strong>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
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
            <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>No. Induk</th><th>Nama</th><th>Kelas</th><th style="width:200px">Status / Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr style="background:{{ $s->jenis_kelamin === 'P' ? '#fde9ec' : '#e6f7ea' }};">
                            <td>{{ $s->id_member }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td>
                                @if ($s->telatHariIni)
                                    <span class="badge-status badge-t">Sudah Terlambat</span>
                                @elseif ($s->absenHariIni)
                                    <span class="badge-status badge-{{ $s->absenHariIni->keterangan }}">
                                        {{ $s->absenHariIni->labelKeterangan() }}
                                    </span>
                                @else
                                    <form method="POST" action="{{ route('absensi.telat', $s) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn-absen btn-absen-telat">
                                            <i class="fas fa-clock me-1"></i> Tandai Terlambat
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>
@endif
@endsection
