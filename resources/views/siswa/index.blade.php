@extends('layouts.app')

@section('title', 'Data Siswa')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-user-graduate fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Daftar Nama Siswa</h1>
    </div>
    <a href="{{ route('siswa.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Siswa
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-4">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama siswa..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-3">
            <select name="kelas" class="form-select">
                <option value="">Semua kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Data siswa tidak ditemukan.
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach ($siswa as $i => $s)
                <a href="{{ route('siswa.profil', $s) }}" class="siswa-baris {{ $s->jenis_kelamin === 'P' ? 'siswa-baris-p' : 'siswa-baris-l' }}">
                    <span class="siswa-no-kecil">{{ $siswa->firstItem() + $i }}</span>
                    <span class="siswa-induk-kecil">{{ $s->id_member }}</span>
                    <span class="siswa-nama-kecil">{{ $s->nama_lengkap }}</span>
                    <span class="siswa-kelas-kecil">{{ $s->kelas }}</span>
                </a>
            @endforeach
        </div>

        {{ $siswa->onEachSide(1)->links() }}
    @endif
</div>
@endsection
