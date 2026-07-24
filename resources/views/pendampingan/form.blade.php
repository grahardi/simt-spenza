@extends('layouts.app')

@php $editMode = isset($pendampingan); @endphp
@section('title', $editMode ? 'Edit Pendampingan' : 'Tambah Pendampingan')

@php
    $isi = fn ($field, $default = '') => old($field, $editMode ? ($pendampingan->{$field} ?? $default) : $default);
    $pesertaTerpilih = $pesertaTerpilih ?? [];
@endphp

@section('content')
@include('partials.menu-wali')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hands-helping me-2"></i>{{ $editMode ? 'Edit Pendampingan' : 'Tambah Pendampingan' }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

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

    <form method="POST" action="{{ $editMode ? route('pendampingan.update', $pendampingan) : route('pendampingan.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editMode) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal &amp; Waktu</label>
                <input type="datetime-local" name="tanggal_waktu" class="form-control"
                       value="{{ old('tanggal_waktu', $editMode ? $pendampingan->tanggal_waktu->format('Y-m-d\TH:i') : now('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    @foreach (\App\Models\PendampinganWali::KATEGORI_PILIHAN as $k)
                        <option value="{{ $k }}" @selected($isi('kategori') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Judul Kegiatan</label>
                <input type="text" name="judul" class="form-control" value="{{ $isi('judul') }}" placeholder="contoh: Konseling motivasi belajar" required>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi (opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ $isi('deskripsi') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label d-block">Peserta</label>
                <div class="form-check form-check-inline">
                    <input type="radio" name="peserta_mode" value="semua" class="form-check-input" id="modeSemua"
                           @checked($isi('peserta_mode', 'semua') === 'semua') onchange="document.getElementById('daftarPeserta').classList.add('d-none')">
                    <label class="form-check-label" for="modeSemua">Semua anak wali ({{ $siswaWali->count() }} siswa)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="peserta_mode" value="pilih" class="form-check-input" id="modePilih"
                           @checked($isi('peserta_mode', 'semua') === 'pilih') onchange="document.getElementById('daftarPeserta').classList.remove('d-none')">
                    <label class="form-check-label" for="modePilih">Pilih tertentu</label>
                </div>

                <div id="daftarPeserta" class="{{ $isi('peserta_mode', 'semua') === 'pilih' ? '' : 'd-none' }} border rounded p-3 mt-2" style="max-height:260px;overflow-y:auto;">
                    @forelse ($siswaWali as $s)
                        <div class="form-check">
                            <input type="checkbox" name="peserta_id[]" value="{{ $s->id_member }}" class="form-check-input" id="peserta{{ $s->id_member }}"
                                   @checked(in_array($s->id_member, $pesertaTerpilih))>
                            <label class="form-check-label" for="peserta{{ $s->id_member }}">{{ $s->nama_lengkap }} ({{ $s->kelas }})</label>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada anak wali terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="col-12">
                <label class="form-label d-block">Visibilitas</label>
                <div class="form-check form-check-inline">
                    <input type="radio" name="visibilitas" value="umum" class="form-check-input" id="visUmum" @checked($isi('visibilitas', 'umum') === 'umum')>
                    <label class="form-check-label" for="visUmum">Umum (tampil di Galeri Wali)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="visibilitas" value="private" class="form-check-input" id="visPrivate" @checked($isi('visibilitas', 'umum') === 'private')>
                    <label class="form-check-label" for="visPrivate">Private (cuma saya yang lihat)</label>
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Foto Utama <span class="text-muted">(tampilan utama &amp; di Galeri Wali)</span></label>
                @if ($editMode && $pendampingan->foto)
                    <div class="mb-2">
                        <img src="{{ Storage::url($pendampingan->foto) }}" class="rounded border" style="max-height:160px;">
                    </div>
                @endif
                <input type="file" name="foto" accept="image/*" class="form-control">
                @if ($editMode && $pendampingan->foto)
                    <small class="text-muted">Upload foto baru untuk mengganti foto utama.</small>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label">Foto Tambahan <span class="text-muted">(boleh pilih beberapa sekaligus)</span></label>
                @if ($editMode && $pendampingan->fotoTambahan->isNotEmpty())
                    <div class="d-flex flex-wrap gap-2 mb-2">
                        @foreach ($pendampingan->fotoTambahan as $ft)
                            <div class="position-relative">
                                <img src="{{ Storage::url($ft->path) }}" class="rounded border" style="width:90px;height:90px;object-fit:cover;">
                                <form method="POST" action="{{ route('pendampingan.foto-tambahan.hapus', $ft) }}" class="position-absolute top-0 end-0" onsubmit="return confirm('Hapus foto ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm py-0 px-1" style="font-size:11px;"><i class="fas fa-times"></i></button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @endif
                <input type="file" name="foto_tambahan[]" accept="image/*" class="form-control" multiple>
                <small class="text-muted">Upload foto baru akan ditambahkan (bukan mengganti yang sudah ada).</small>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('pendampingan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
