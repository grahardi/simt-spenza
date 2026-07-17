@extends('layouts.adminlte')

@section('title', $guru->exists ? 'Edit Guru' : 'Tambah Guru')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ $guru->exists ? 'Edit Guru' : 'Tambah Guru' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ $guru->exists ? route('superadmin.guru.update', $guru) : route('superadmin.guru.store') }}">
            @csrf
            @if ($guru->exists) @method('PUT') @endif

            <div class="row">
                <div class="form-group col-md-4">
                    <label>NIP</label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $guru->nip) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>NUPTK</label>
                    <input type="text" name="nuptk" class="form-control" value="{{ old('nuptk', $guru->nuptk) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $guru->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $guru->nama) }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', optional($guru->tgl_lahir)->format('Y-m-d')) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>Jabatan (Mengajar)</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $guru->jabatan) }}" placeholder="contoh: Guru Mapel, Wali Kelas">
                </div>
                <div class="form-group col-md-6">
                    <label>Status Kepegawaian</label>
                    <input type="text" name="status" class="form-control" value="{{ old('status', $guru->status) }}" placeholder="contoh: PNS, GTT">
                </div>
                <div class="form-group col-md-6">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $guru->telepon) }}">
                </div>
                <div class="form-group col-12">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $guru->alamat) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.guru.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
