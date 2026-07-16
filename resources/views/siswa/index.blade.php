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
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NISN</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>L/P</th>
                        <th>WhatsApp</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $i => $s)
                        <tr>
                            <td>{{ $siswa->firstItem() + $i }}</td>
                            <td>{{ $s->nisn }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td>{{ $s->jenis_kelamin }}</td>
                            <td>{{ $s->whatsapp }}</td>
                            <td class="text-end">
                                <a href="{{ route('siswa.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('siswa.destroy', $s) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data siswa ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $siswa->onEachSide(1)->links() }}
    @endif
</div>
@endsection
