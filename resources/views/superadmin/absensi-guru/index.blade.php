@extends('layouts.adminlte')

@section('title', 'Data Absensi Guru')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Data Absensi Guru (semua tanggal)</h3></div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="tgl" class="form-control" value="{{ request('tgl') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Tanggal</th><th>Guru</th><th>Status</th><th>Keterangan</th><th>Foto</th><th style="width:160px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($absensi as $a)
                    <tr>
                        <td>{{ $a->tanggal->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->guru->nama ?? '-' }}</td>
                        <td>{{ $a->labelStatus() }}</td>
                        <td>{{ $a->keterangan }}</td>
                        <td>
                            @if ($a->foto)
                                <a href="{{ Storage::url($a->foto) }}" target="_blank"><i class="fas fa-image"></i></a>
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.absensi-guru.edit', $a) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('superadmin.absensi-guru.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $absensi->onEachSide(1)->links() }}
    </div>
</div>
@endsection
