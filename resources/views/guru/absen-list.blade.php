@extends('layouts.app')

@section('title', 'Absen Guru')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Absen Guru</h1>
    </div>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($guru->isEmpty())
        <div class="text-muted text-center py-4">Data guru tidak ditemukan.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead><tr><th>No</th><th>Nama</th><th>Jabatan</th><th></th></tr></thead>
                <tbody>
                    @foreach ($guru as $i => $g)
                        <tr>
                            <td>{{ $guru->firstItem() + $i }}</td>
                            <td>{{ $g->nama }}</td>
                            <td>{{ $g->jabatan }}</td>
                            <td class="text-end">
                                <a href="{{ route('jadwal.guru', $g) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-calendar-alt me-1"></i> Lihat Jadwal
                                </a>
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
