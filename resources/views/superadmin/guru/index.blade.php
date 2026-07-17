@extends('layouts.adminlte')

@section('title', 'Data Guru')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Guru</h3>
        <a href="{{ route('superadmin.guru.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Guru</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Status</th><th style="width:260px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($guru as $g)
                    <tr class="{{ !$g->aktif ? 'text-muted' : '' }}">
                        <td>{{ $g->nama }}</td>
                        <td>{{ $g->nip }}</td>
                        <td>{{ $g->jabatan }}</td>
                        <td>
                            @if ($g->aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.guru.edit', $g) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
                            <a href="{{ route('superadmin.guru.roles', $g) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-user-shield"></i> Roles</a>
                            <form method="POST" action="{{ route('superadmin.guru.toggle-aktif', $g) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin {{ $g->aktif ? 'nonaktifkan' : 'aktifkan' }} {{ $g->nama }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs {{ $g->aktif ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    <i class="fas fa-power-off"></i> {{ $g->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Data guru tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $guru->onEachSide(1)->links() }}
    </div>
</div>
@endsection
