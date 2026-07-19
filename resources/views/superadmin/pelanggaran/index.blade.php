@extends('layouts.adminlte')

@section('title', 'Data Pelanggaran')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Data Pelanggaran (semua tahun)</h3></div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama siswa..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kategori</th><th>Poin</th><th>Penanganan</th><th style="width:160px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($pelanggaran as $p)
                    <tr>
                        <td>{{ $p->tgl_pelanggaran->translatedFormat('d M Y') }}</td>
                        <td>{{ $p->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $p->kategori }}</td>
                        <td>{{ $p->poin }}</td>
                        <td>{{ $p->penanganan }}</td>
                        <td>
                            <a href="{{ route('superadmin.pelanggaran.edit', $p) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('superadmin.pelanggaran.destroy', $p) }}" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
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
        {{ $pelanggaran->onEachSide(1)->links() }}
    </div>
</div>
@endsection
