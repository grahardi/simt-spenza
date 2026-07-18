@extends('layouts.app')

@section('title', 'Surat Masuk')

@section('content')
@include('persuratan._menu')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-inbox fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Surat Masuk</h1>
    </div>
    <a href="{{ route('surat-masuk.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Catat Surat Masuk
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari perihal, asal surat, atau nomor..." value="{{ request('cari') }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($surat->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada surat masuk tercatat.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tgl Terima</th>
                        <th>Nomor Surat</th>
                        <th>Asal</th>
                        <th>Perihal</th>
                        <th>Disposisi</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surat as $i => $s)
                        <tr>
                            <td>{{ $surat->firstItem() + $i }}</td>
                            <td>{{ $s->tanggal_terima->format('d/m/Y') }}</td>
                            <td>{{ $s->nomor_surat }}</td>
                            <td>{{ $s->asal_surat }}</td>
                            <td>{{ $s->perihal }}</td>
                            <td>{{ $s->disposisi_ke ?: '-' }}</td>
                            <td>
                                <span class="badge {{ $s->status === 'selesai' ? 'bg-success' : ($s->status === 'diproses' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                    {{ $s->labelStatus() }}
                                </span>
                            </td>
                            <td class="text-end">
                                @if ($s->file_scan)
                                    <a href="{{ Storage::url($s->file_scan) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-file"></i>
                                    </a>
                                @endif
                                <a href="{{ route('surat-masuk.edit', $s) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('surat-masuk.destroy', $s) }}" method="POST" class="d-inline"
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
