@extends('layouts.app')

@section('title', 'Data Guru')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Guru</h1>
    </div>
    <a href="{{ route('guru.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Guru
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($guru->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Data guru tidak ditemukan.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>NIP</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Telepon</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($guru as $i => $g)
                        <tr>
                            <td>{{ $guru->firstItem() + $i }}</td>
                            <td>{{ $g->nip }}</td>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->jabatan }}</td>
                            <td>{{ $g->telepon }}</td>
                            <td class="text-end">
                                <a href="{{ route('guru.edit', $g) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('guru.destroy', $g) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus data guru ini?')">
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

        {{ $guru->onEachSide(1)->links() }}
    @endif
</div>
@endsection
