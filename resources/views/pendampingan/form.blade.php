@extends('layouts.app')

@section('title', 'Tambah Pendampingan')

@section('content')
@include('partials.menu-wali')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hands-helping me-2"></i>Tambah Pendampingan</h1>
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

    <form method="POST" action="{{ route('pendampingan.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label">Tanggal &amp; Waktu</label>
                <input type="datetime-local" name="tanggal_waktu" class="form-control" value="{{ old('tanggal_waktu', now('Asia/Jakarta')->format('Y-m-d\TH:i')) }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Kategori</label>
                <select name="kategori" class="form-select" required>
                    @foreach (\App\Models\PendampinganWali::KATEGORI_PILIHAN as $k)
                        <option value="{{ $k }}" @selected(old('kategori') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12">
                <label class="form-label">Judul Kegiatan</label>
                <input type="text" name="judul" class="form-control" value="{{ old('judul') }}" placeholder="contoh: Konseling motivasi belajar" required>
            </div>
            <div class="col-12">
                <label class="form-label">Deskripsi (opsional)</label>
                <textarea name="deskripsi" class="form-control" rows="3">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label d-block">Peserta</label>
                <div class="form-check form-check-inline">
                    <input type="radio" name="peserta_mode" value="semua" class="form-check-input" id="modeSemua" checked onchange="document.getElementById('daftarPeserta').classList.add('d-none')">
                    <label class="form-check-label" for="modeSemua">Semua anak wali ({{ $siswaWali->count() }} siswa)</label>
                </div>
                <div class="form-check form-check-inline">
                    <input type="radio" name="peserta_mode" value="pilih" class="form-check-input" id="modePilih" onchange="document.getElementById('daftarPeserta').classList.remove('d-none')">
                    <label class="form-check-label" for="modePilih">Pilih tertentu</label>
                </div>

                <div id="daftarPeserta" class="d-none border rounded p-3 mt-2" style="max-height:260px;overflow-y:auto;">
                    @forelse ($siswaWali as $s)
                        <div class="form-check">
                            <input type="checkbox" name="peserta_id[]" value="{{ $s->id_member }}" class="form-check-input" id="peserta{{ $s->id_member }}">
                            <label class="form-check-label" for="peserta{{ $s->id_member }}">{{ $s->nama_lengkap }} ({{ $s->kelas }})</label>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada anak wali terdaftar.</p>
                    @endforelse
                </div>
            </div>

            <div class="col-12">
                <label class="form-label">Foto Kegiatan (opsional)</label>
                <input type="file" name="foto" accept="image/*" class="form-control">
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('pendampingan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
