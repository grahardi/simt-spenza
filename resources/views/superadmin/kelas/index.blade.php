@extends('layouts.adminlte')

@section('title', 'Data Kelas')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Kelas</h3>
        <a href="{{ route('superadmin.kelas.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Kelas</a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Nama Kelas</th><th>Kapasitas</th><th style="width:100px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($kelas as $k)
                    <tr>
                        <td>{{ $k->nama_kelas }}</td>
                        <td>{{ $k->jumlah }}</td>
                        <td><a href="{{ route('superadmin.kelas.edit', $k) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada data kelas.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $kelas->onEachSide(1)->links() }}
    </div>
</div>
@endsection
