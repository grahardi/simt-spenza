@extends('layouts.adminlte')

@section('title', 'Data Karyawan')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Data Karyawan</h3>
        <a href="{{ route('superadmin.karyawan.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Tambah Karyawan</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama karyawan..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Semua status</option>
                    <option value="aktif" @selected(request('status') === 'aktif')>Aktif</option>
                    <option value="nonaktif" @selected(request('status') === 'nonaktif')>Nonaktif</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Nama</th><th>NIP</th><th>Jabatan</th><th>Status</th><th style="width:260px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($karyawan as $k)
                    <tr class="{{ !$k->aktif ? 'text-muted' : '' }}">
                        <td>{{ $k->nama }}</td>
                        <td>{{ $k->nip }}</td>
                        <td>{{ $k->jabatan }}</td>
                        <td>
                            @if ($k->aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-secondary">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.karyawan.edit', $k) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i> Edit</a>
                            <a href="{{ route('superadmin.karyawan.roles', $k) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-user-shield"></i> Roles</a>
                            <form method="POST" action="{{ route('superadmin.karyawan.toggle-aktif', $k) }}" class="d-inline"
                                  onsubmit="return confirm('Yakin {{ $k->aktif ? 'nonaktifkan' : 'aktifkan' }} {{ $k->nama }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs {{ $k->aktif ? 'btn-outline-danger' : 'btn-outline-success' }}">
                                    <i class="fas fa-power-off"></i> {{ $k->aktif ? 'Nonaktifkan' : 'Aktifkan' }}
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Data karyawan tidak ditemukan.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $karyawan->onEachSide(1)->links() }}
    </div>
</div>
@endsection
