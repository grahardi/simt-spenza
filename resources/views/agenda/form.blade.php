@extends('layouts.app')

@php $editMode = $agenda->exists; @endphp
@section('title', $editMode ? 'Edit Agenda' : 'Tambah Agenda')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-calendar-alt me-2"></i>{{ $editMode ? 'Edit Agenda' : 'Tambah Agenda' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ $editMode ? route('agenda.update', $agenda) : route('agenda.store') }}">
        @csrf
        @if ($editMode) @method('PUT') @endif

        <div class="mb-3">
            <label class="form-label">Judul Kegiatan</label>
            <input type="text" name="judul" class="form-control" value="{{ old('judul', $agenda->judul) }}" required>
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

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('agenda.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
