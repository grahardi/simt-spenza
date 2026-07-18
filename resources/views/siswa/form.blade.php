@extends('layouts.app')

@section('title', $siswa->exists ? 'Ubah Siswa' : 'Tambah Siswa')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">{{ $siswa->exists ? 'Ubah Data Siswa' : 'Tambah Data Siswa' }}</h1>
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

    <form method="POST" action="{{ $siswa->exists ? route('siswa.update', $siswa) : route('siswa.store') }}">
        @csrf
        @if ($siswa->exists) @method('PUT') @endif

        <div class="row g-3">
            <div class="col-md-4">
                <label class="form-label">NISN</label>
                <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Kelas</label>
                <input type="text" name="kelas" class="form-control" placeholder="contoh: 9 - A" value="{{ old('kelas', $siswa->kelas) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="col-md-8">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Nomor Bangku</label>
                <input type="number" name="nomer_bangku" class="form-control" value="{{ old('nomer_bangku', $siswa->nomer_bangku) }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">WhatsApp</label>
                <p class="form-control-plaintext text-muted small mb-0">
                    Sistem sekarang mendukung lebih dari 1 nomor per siswa (Ayah/Ibu/Wali).
                    Kelola di menu <strong>Nomor WA Terdaftar</strong> (Superadmin) atau minta wali murid
                    registrasi sendiri lewat bot WhatsApp.
                </p>
            </div>
            <div class="col-md-6">
                <label class="form-label">Email</label>
                <input type="text" name="email" class="form-control" value="{{ old('email', $siswa->email) }}">
            </div>
            <div class="col-12">
                <label class="form-label">Alamat</label>
                <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
