@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Kategori' : 'Tambah Kategori')

@section('content')
@include('persuratan._menu')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">{{ $item->exists ? 'Ubah Kategori Surat' : 'Tambah Kategori Surat' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('kategori-surat.update', $item) : route('kategori-surat.store') }}">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Kode Umum</label>
                <input type="text" name="kode" class="form-control" value="{{ old('kode', $item->kode) }}" placeholder="contoh: 400" required>
            </div>
            <div class="col-md-8">
                <label class="form-label">Nama Kategori</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $item->nama) }}" placeholder="contoh: Kesiswaan" required>
            </div>
            <div class="col-12">
                <label class="form-label">Keterangan (opsional)</label>
                <input type="text" name="keterangan" class="form-control" value="{{ old('keterangan', $item->keterangan) }}">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('kategori-surat.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
