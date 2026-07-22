@extends('layouts.app')

@php $editMode = isset($ajuan); @endphp
@section('title', $editMode ? 'Edit Surat Permohonan' : 'Buat Surat Permohonan')

@php
    $isi = fn ($field, $default = '') => old($field, $editMode ? ($ajuan->data[$field] ?? $default) : $default);
@endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-alt me-2"></i>{{ $editMode ? 'Edit Surat Permohonan' : 'Buat Surat Permohonan' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:640px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="text-muted small">Hari dihitung otomatis dari tanggal kegiatan.</p>

    <form method="POST" action="{{ $editMode ? route('ajuan-surat.permohonan.update', $ajuan) : route('surat-tu.permohonan.store') }}">
        @csrf
        @if ($editMode) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Ditujukan Kepada (Yth.)</label>
                <input type="text" name="tujuan" class="form-control" value="{{ $isi('tujuan') }}" placeholder="contoh: Kepala Dinas Pendidikan Kab. Malang" required>
            </div>
            <div class="col-12">
                <label class="form-label">Alamat Tujuan</label>
                <input type="text" name="alamat" class="form-control" value="{{ $isi('alamat') }}" placeholder="contoh: Jl. ..." required>
            </div>
            <div class="col-12">
                <label class="form-label">Kota</label>
                <input type="text" name="kota" class="form-control" value="{{ $isi('kota') }}" placeholder="contoh: Malang" required>
            </div>
            <div class="col-12">
                <label class="form-label">Kegiatan</label>
                <input type="text" name="kegiatan" class="form-control" value="{{ $isi('kegiatan') }}" placeholder="contoh: Pentas Seni Akhir Tahun" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Surat <span class="text-muted">(tanggal terbit, default hari ini)</span></label>
                <input type="date" name="tanggal_surat" class="form-control" value="{{ $isi('tanggal_surat', now('Asia/Jakarta')->toDateString()) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Kegiatan</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $isi('tanggal') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Waktu</label>
                <input type="time" name="waktu" class="form-control" value="{{ $isi('waktu', '08:00') }}" lang="id" step="60" required>
            </div>
            <div class="col-12">
                <label class="form-label">Tempat</label>
                <input type="text" name="tempat" class="form-control" value="{{ $isi('tempat') }}" placeholder="contoh: Aula SMP Negeri 1 Turen" required>
            </div>
            <div class="col-12">
                <label class="form-label">Permohonan/Tindakan yang Diminta</label>
                <textarea name="tindakan" class="form-control" rows="2" placeholder="contoh: berkenan hadir dan memberikan sambutan">{{ $isi('tindakan') }}</textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-{{ $editMode ? 'save' : 'paper-plane' }} me-1"></i> {{ $editMode ? 'Simpan Perubahan' : 'Buat Surat' }}
            </button>
            <a href="{{ $editMode ? route('surat-tu.show', $ajuan) : route('surat-tu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
