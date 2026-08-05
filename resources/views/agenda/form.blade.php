@extends('layouts.app')

@php $editMode = $agenda->exists; @endphp
@section('title', $editMode ? 'Edit Agenda' : 'Tambah Agenda')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ $editMode ? 'Edit Agenda' : 'Tambah Agenda' }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

{{-- Berkas Lainnya (yang sudah ada) diletakkan DI LUAR form utama - supaya
     form hapus per-berkas tidak bersarang di dalam form utama (form di dalam
     form itu HTML tidak valid dan bikin tombol Simpan gagal berfungsi). --}}
@if ($editMode && $agenda->berkasLainnya->isNotEmpty())
    <div class="p-4 bg-white rounded shadow mb-3" style="max-width:560px;">
        <label class="form-label d-block">Berkas Lainnya - Sudah Diupload</label>
        <ul class="list-group">
            @foreach ($agenda->berkasLainnya as $b)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <a href="{{ Storage::url($b->path) }}" target="_blank">{{ $b->nama_file }}</a>
                    <form method="POST" action="{{ route('agenda.berkas-lainnya.hapus', $b) }}" onsubmit="return confirm('Hapus berkas ini?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    <form method="POST" action="{{ $editMode ? route('agenda.update', $agenda) : route('agenda.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editMode) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Judul Kegiatan</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $agenda->judul) }}" required>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Ketua Panitia <span class="text-muted">(opsional)</span></label>
                <select name="ketua" class="form-select">
                    <option value="">- Pilih guru -</option>
                    @foreach ($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}" @selected(old('ketua', $agenda->ketua()?->id_guru) == $g->id_guru)>{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label">Sekretaris <span class="text-muted">(opsional)</span></label>
                <select name="sekretaris" class="form-select">
                    <option value="">- Pilih guru -</option>
                    @foreach ($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}" @selected(old('sekretaris', $agenda->sekretaris()?->id_guru) == $g->id_guru)>{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row g-3 mb-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal Mulai</label>
                <input type="date" name="tanggal_mulai" class="form-control" value="{{ old('tanggal_mulai', $agenda->tanggal_mulai?->format('Y-m-d')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Selesai <span class="text-muted">(opsional, kosongkan kalau 1 hari)</span></label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai', $agenda->tanggal_selesai?->format('Y-m-d')) }}">
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
                @foreach (\App\Models\Agenda::KATEGORI_LABEL as $kode => $label)
                    <option value="{{ $kode }}" @selected(old('kategori', $agenda->kategori) === $kode)>{{ $label }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan (opsional)</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $agenda->keterangan) }}</textarea>
        </div>

        <hr>
        <p class="text-muted small mb-2">Upload berkas kegiatan (opsional, boleh dilengkapi belakangan).</p>

        <div class="mb-3">
            <label class="form-label">Proposal Kegiatan</label>
            @if ($agenda->berkas_proposal)
                <div class="mb-1"><a href="{{ Storage::url($agenda->berkas_proposal) }}" target="_blank"><i class="fas fa-file me-1"></i>Berkas saat ini</a></div>
            @endif
            <input type="file" name="berkas_proposal" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">SK Kepanitiaan</label>
            @if ($agenda->berkas_sk_kepanitiaan)
                <div class="mb-1"><a href="{{ Storage::url($agenda->berkas_sk_kepanitiaan) }}" target="_blank"><i class="fas fa-file me-1"></i>Berkas saat ini</a></div>
            @endif
            <input type="file" name="berkas_sk_kepanitiaan" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">SPJ Kegiatan</label>
            @if ($agenda->berkas_spj)
                <div class="mb-1"><a href="{{ Storage::url($agenda->berkas_spj) }}" target="_blank"><i class="fas fa-file me-1"></i>Berkas saat ini</a></div>
            @endif
            <input type="file" name="berkas_spj" class="form-control">
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('agenda.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>

@if ($editMode)
    <div class="p-4 bg-white rounded shadow mt-3" style="max-width:560px;">
        <label class="form-label">Berkas Lainnya <span class="text-muted">(boleh upload beberapa sekaligus)</span></label>
        <form method="POST" action="{{ route('agenda.berkas-lainnya.store', $agenda) }}" enctype="multipart/form-data" class="d-flex gap-2">
            @csrf
            <input type="file" name="berkas[]" class="form-control" multiple required>
            <button type="submit" class="btn btn-outline-primary text-nowrap">Upload</button>
        </form>
    </div>
@endif
@endsection
