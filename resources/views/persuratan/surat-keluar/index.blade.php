@extends('layouts.app')

@section('title', 'Surat Keluar')

@section('content')
@include('persuratan._menu')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-paper-plane fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Surat Keluar</h1>
    </div>
    <a href="{{ route('surat-keluar.pilih-jenis') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Surat
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari perihal, tujuan, atau kode surat..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($surat->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada surat keluar tercatat.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Surat</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Tujuan</th>
                        <th>Perihal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surat as $i => $s)
                        <tr>
                            <td>{{ $surat->firstItem() + $i }}</td>
                            <td><code>{{ $s->kode_surat }}</code></td>
                            <td>{{ $s->kategori->nama ?? '-' }}</td>
                            <td>{{ $s->tanggal_surat->format('d/m/Y') }}</td>
                            <td>{{ $s->tujuan_surat }}</td>
                            <td>{{ $s->perihal }}</td>
                            <td class="text-end">
                                @if ($s->lampiran)
                                    <a href="{{ Storage::url($s->lampiran) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-paperclip"></i>
                                    </a>
                                @endif
                                <a href="{{ route('surat-keluar.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('surat-keluar.destroy', $s) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus surat ini?')">
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
        {{ $surat->onEachSide(1)->links() }}
    @endif
</div>
@endsection
