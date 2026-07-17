@extends('layouts.adminlte')

@section('title', 'Kelola Akun')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Kelola Akun Login</h3>
        <a href="{{ route('superadmin.akun.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat Akun</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama/nomor ID..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead><tr><th>Nomor ID</th><th>Nama</th><th>Terhubung Guru</th><th>Roles</th><th style="width:100px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($akun as $a)
                    <tr>
                        <td>{{ $a->user }}</td>
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->guru->nama ?? '-' }}</td>
                        <td>
                            @forelse ($a->roles() as $r)
                                <span class="badge badge-secondary">{{ $r }}</span>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td><a href="{{ route('superadmin.akun.edit', $a) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-user-shield"></i> Kelola</a></td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada akun.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $akun->onEachSide(1)->links() }}
    </div>
</div>
@endsection
