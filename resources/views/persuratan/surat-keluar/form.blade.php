@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">{{ $item->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar' }}</h1>
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

    @if (!$item->exists)
        <div class="alert alert-info small">
            <i class="fas fa-info-circle me-1"></i>
            Nomor surat otomatis (perkiraan): <strong>{{ $preview['kode_surat'] }}</strong> — nomor pasti dihitung
            ulang saat disimpan (supaya tidak dobel kalau ada yang buat surat bersamaan).
        </div>
    @else
        <div class="alert alert-secondary small">
            Kode Surat: <strong>{{ $item->kode_surat }}</strong> (tidak berubah setelah dibuat)
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('surat-keluar.update', $item) : route('surat-keluar.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', optional($item->tanggal_surat)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tujuan Surat</label>
                <input type="text" name="tujuan_surat" class="form-control" value="{{ old('tujuan_surat', $item->tujuan_surat) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Perihal</label>
                <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $item->perihal) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Lampiran (opsional)</label>
                <input type="file" name="lampiran" class="form-control" accept="image/*,.pdf,.doc,.docx">
                @if ($item->lampiran)
                    <small class="text-muted">File saat ini: <a href="{{ Storage::url($item->lampiran) }}" target="_blank">lihat file</a> (upload baru untuk mengganti)</small>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('surat-keluar.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
