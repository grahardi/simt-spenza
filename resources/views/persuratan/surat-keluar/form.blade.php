@extends('layouts.app')

@section('title', $item->exists ? 'Ubah Surat Keluar' : 'Buat Surat Keluar')

@section('content')
@include('persuratan._menu')
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

    @if ($item->exists)
        <div class="alert alert-secondary small">
            Kode Surat: <strong>{{ $item->kode_surat }}</strong> (tidak berubah setelah dibuat)
        </div>
    @endif

    <form method="POST" action="{{ $item->exists ? route('surat-keluar.update', $item) : route('surat-keluar.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($item->exists) @method('PUT') @endif

        <div class="row g-3">
            @if (! $item->exists)
                <div class="col-md-6">
                    <label class="form-label">Kategori Surat (Kode Umum)</label>
                    <select name="id_kategori_surat" class="form-select" required>
                        <option value="">- Pilih kategori -</option>
                        @foreach ($daftarKategori as $k)
                            <option value="{{ $k->id }}" @selected(old('id_kategori_surat') == $k->id)>{{ $k->kode }} - {{ $k->nama }}</option>
                        @endforeach
                    </select>
                    @if ($daftarKategori->isEmpty())
                        <small class="text-danger">Belum ada kategori surat - <a href="{{ route('kategori-surat.create') }}">buat dulu di sini</a>.</small>
                    @endif
                </div>
                <div class="col-md-6">
                    <label class="form-label">Nomor Urut</label>
                    <div class="d-flex gap-3 mb-2">
                        <div class="form-check">
                            <input type="radio" name="mode_nomor" value="auto" class="form-check-input" id="modeAuto" checked onchange="document.getElementById('nomorManual').disabled = true">
                            <label class="form-check-label" for="modeAuto">Otomatis (nomor berikutnya: <strong>{{ $nomorUrutBerikutnya }}</strong>)</label>
                        </div>
                        <div class="form-check">
                            <input type="radio" name="mode_nomor" value="manual" class="form-check-input" id="modeManual" onchange="document.getElementById('nomorManual').disabled = false">
                            <label class="form-check-label" for="modeManual">Manual</label>
                        </div>
                    </div>
                    <input type="number" name="nomor_urut_manual" id="nomorManual" class="form-control" placeholder="Isi nomor urut manual" disabled value="{{ old('nomor_urut_manual') }}">
                </div>
            @endif
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
