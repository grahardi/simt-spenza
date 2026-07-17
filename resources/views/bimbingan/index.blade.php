@extends('layouts.app')

@section('title', 'Data Bimbingan Konseling')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-hands-helping fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Bimbingan Konseling</h1>
    </div>
    <a href="{{ route('bimbingan.cari') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Catatan
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($bimbingan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada catatan bimbingan pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Jenis</th>
                        <th>Keterangan</th>
                        <th>Tindakan</th>
                        <th>Foto</th>
                        <th>Pelapor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bimbingan as $i => $b)
                        <tr>
                            <td>{{ $bimbingan->firstItem() + $i }}</td>
                            <td>{{ $b->siswa->nama_lengkap ?? '-' }}</td>
                            <td><span class="badge-status badge-purple" style="background:#eeedfe;color:#534ab7;">{{ $b->kategori }}</span></td>
                            <td class="small">{{ $b->Keterangan }}</td>
                            <td class="small">{{ $b->Tindakan }}</td>
                            <td>
                                @if ($b->gambar)
                                    <a href="{{ Storage::url($b->gambar) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-image"></i>
                                    </a>
                                @else
                                    <span class="text-muted small">-</span>
                                @endif
                            </td>
                            <td>{{ $b->pelapor->nama ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $bimbingan->onEachSide(1)->links() }}
    @endif
</div>
@endsection
