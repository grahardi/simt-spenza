@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Surat Masuk' : 'Catat Surat Masuk')

@section('content')
@include('persuratan._menu')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">{{ $item->exists ? 'Ubah Surat Masuk' : 'Catat Surat Masuk' }}</h1>
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

    <form method="POST" action="{{ $item->exists ? route('surat-masuk.update', $item) : route('surat-masuk.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Nomor Surat (dari pengirim)</label>
                <input type="text" name="nomor_surat" class="form-control" value="{{ old('nomor_surat', $item->nomor_surat) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Asal Surat</label>
                <input type="text" name="asal_surat" class="form-control" value="{{ old('asal_surat', $item->asal_surat) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Surat</label>
                <input type="date" name="tanggal_surat" class="form-control" value="{{ old('tanggal_surat', optional($item->tanggal_surat)->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Diterima</label>
                <input type="date" name="tanggal_terima" class="form-control" value="{{ old('tanggal_terima', optional($item->tanggal_terima)->format('Y-m-d') ?? date('Y-m-d')) }}" required>
            </div>
            <div class="col-12">
                <label class="form-label">Perihal</label>
                <input type="text" name="perihal" class="form-control" value="{{ old('perihal', $item->perihal) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Disposisi Ke (opsional)</label>
                <input type="text" name="disposisi_ke" class="form-control" value="{{ old('disposisi_ke', $item->disposisi_ke) }}" placeholder="contoh: Kepala Sekolah, Bag. Kurikulum">
            </div>
            @if ($item->exists)
                <div class="col-md-6">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="baru" @selected($item->status === 'baru')>Baru</option>
                        <option value="diproses" @selected($item->status === 'diproses')>Diproses</option>
                        <option value="selesai" @selected($item->status === 'selesai')>Selesai</option>
                    </select>
                </div>
            @endif
            <div class="col-12">
                <label class="form-label">Catatan Disposisi (opsional)</label>
                <textarea name="catatan_disposisi" class="form-control" rows="2">{{ old('catatan_disposisi', $item->catatan_disposisi) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">File Scan Surat (opsional)</label>
                <input type="file" name="file_scan" class="form-control" accept="image/*,.pdf">
                @if ($item->file_scan)
                    <small class="text-muted">File saat ini: <a href="{{ Storage::url($item->file_scan) }}" target="_blank">lihat file</a> (upload baru untuk mengganti)</small>
                @endif
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('surat-masuk.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
