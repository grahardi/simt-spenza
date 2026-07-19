@extends('layouts.adminlte')

@section('title', 'Data Bimbingan Konseling')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Data Bimbingan Konseling</h3></div>
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
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Jenis</th><th>Tindakan</th><th style="width:160px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($bk as $b)
                    <tr>
                        <td>{{ $b->tgl_bimbingan->translatedFormat('d M Y') }}</td>
                        <td>{{ $b->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $b->kategori }}</td>
                        <td>{{ $b->Tindakan }}</td>
                        <td>
                            <a href="{{ route('superadmin.bk.edit', $b) }}" class="btn btn-xs btn-outline-primary"><i class="fas fa-edit"></i></a>
                            <form method="POST" action="{{ route('superadmin.bk.destroy', $b) }}" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $bk->onEachSide(1)->links() }}
    </div>
</div>
@endsection
