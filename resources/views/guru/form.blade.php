@extends('layouts.app')

@section('title', $guru->exists ? 'Ubah Guru' : 'Tambah Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">{{ $guru->exists ? 'Ubah Data Guru' : 'Tambah Data Guru' }}</h1>
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

    <form method="POST" action="{{ $guru->exists ? route('guru.update', $guru) : route('guru.store') }}">
        @csrf
        @if ($guru->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">NIP</label>
                <input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">NUPTK</label>
                <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="L" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', optional($guru->tgl_lahir)->format('Y-m-d')) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Jabatan</label>
                <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $guru->jabatan) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Status Kepegawaian</label>
                <input type="text" name="status" class="form-control" value="{{ old('status', $guru->status) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Telepon</label>
                <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $guru->telepon) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('guru.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
