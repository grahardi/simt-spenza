@extends('layouts.adminlte')

@section('title', 'Data Absensi')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Data Absensi (semua tanggal)</h3></div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="tgl" class="form-control" value="{{ request('tgl') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="kelas" class="form-control" placeholder="Kelas, mis. 7 - A" value="{{ request('kelas') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <table class="table table-bordered table-striped">
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Keterangan</th><th style="width:160px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($absensi as $a)
                    <tr>
                        <td>{{ $a->tgl_absen->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $a->siswa->kelas ?? '-' }}</td>
                        <td>{{ $a->labelKeterangan() }}</td>
                        <td>{{ $a->tambahan }}</td>
                        <td>
                            <a href="{{ route('superadmin.absensi.edit', $a) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('superadmin.absensi.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
        {{ $absensi->onEachSide(1)->links() }}
    </div>
</div>
@endsection
