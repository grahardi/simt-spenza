@extends('layouts.adminlte')

@section('title', 'Data Siswa')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Siswa</h3>
        <a href="{{ route('superadmin.siswa.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Siswa</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-3">
                <select name="kelas" class="form-control">
                    <option value="">Semua kelas</option>
                    @foreach ($daftarKelas as $k)
                        <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead><tr><th>No. Induk</th><th>Nama</th><th>Kelas</th><th>L/P</th><th style="width:160px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr>
                        <td>{{ $s->id_member }}</td>
                        <td>{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->kelas }}</td>
                        <td>{{ $s->jenis_kelamin }}</td>
                        <td>
                            <a href="{{ route('superadmin.siswa.edit', $s) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
                            <a href="{{ route('superadmin.siswa.mutasi-form', $s) }}" class="btn btn-xs btn-outline-warning"><i class="fas fa-exchange-alt"></i> Mutasi</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Data siswa tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $siswa->onEachSide(1)->links() }}
    </div>
</div>
@endsection
