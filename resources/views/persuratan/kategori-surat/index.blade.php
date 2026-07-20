@extends('layouts.app')

@section('title', 'Kategori Surat')

@section('content')
@include('persuratan._menu')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-tags fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Kategori Surat</h1>
    </div>
    <a href="{{ route('kategori-surat.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Tambah Kategori
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <h6 class="mb-2"><i class="fas fa-cog me-1"></i> Pengaturan Kode Baku</h6>
    <p class="text-muted small mb-2">
        Kode tetap sekolah yang dipakai di setiap nomor surat keluar (contoh: <code>35.07.301.09.43</code>).
    </p>
    <form method="POST" action="{{ route('kategori-surat.pengaturan') }}" class="row g-2">
        @csrf
        <div class="col-md-4">
            <label class="form-label small">Kode Baku</label>
            <input type="text" name="kode_baku" class="form-control" value="{{ $pengaturan->kode_baku }}" required>
        </div>
        <div class="col-md-3">
            <label class="form-label small">Nama Kepala Sekolah</label>
            <input type="text" name="kepsek_nama" class="form-control" value="{{ $pengaturan->kepsek_nama }}" placeholder="untuk tanda tangan surat dinas">
        </div>
        <div class="col-md-3">
            <label class="form-label small">NIP Kepala Sekolah</label>
            <input type="text" name="kepsek_nip" class="form-control" value="{{ $pengaturan->kepsek_nip }}">
        </div>
        <div class="col-md-2">
            <label class="form-label small">Pangkat Kepsek</label>
            <input type="text" name="kepsek_pangkat" class="form-control" value="{{ $pengaturan->kepsek_pangkat }}">
        </div>
        <div class="col-12">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h6 class="mb-3">Daftar Kategori (Kode Umum)</h6>
    @if ($kategori->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada kategori surat.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr><th>Kode</th><th>Nama Kategori</th><th>Keterangan</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($kategori as $k)
                        <tr>
                            <td><code>{{ $k->kode }}</code></td>
                            <td>{{ $k->nama }}</td>
                            <td>{{ $k->keterangan }}</td>
                            <td class="text-end">
                                <a href="{{ route('kategori-surat.edit', $k) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('kategori-surat.destroy', $k) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus kategori ini?')">
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
    @endif
</div>
@endsection
