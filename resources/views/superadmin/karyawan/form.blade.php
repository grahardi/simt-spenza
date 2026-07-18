@extends('layouts.adminlte')

@section('title', $karyawan->exists ? 'Edit Karyawan' : 'Tambah Karyawan')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ $karyawan->exists ? 'Edit Karyawan' : 'Tambah Karyawan' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ $karyawan->exists ? route('superadmin.karyawan.update', $karyawan) : route('superadmin.karyawan.store') }}">
            @csrf
            @if ($karyawan->exists) @method('PUT') @endif

            <div class="row">
                <div class="form-group col-md-4">
                    <label>NIP (opsional)</label>
                    <input type="text" name="nip" class="form-control" value="{{ old('nip', $karyawan->nip) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control">
                        <option value="">-</option>
                        <option value="L" @selected(old('jenis_kelamin', $karyawan->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $karyawan->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Tanggal Lahir</label>
                    <input type="date" name="tgl_lahir" class="form-control" value="{{ old('tgl_lahir', optional($karyawan->tgl_lahir)->format('Y-m-d')) }}">
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control" value="{{ old('nama', $karyawan->nama) }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Jabatan</label>
                    <input type="text" name="jabatan" class="form-control" value="{{ old('jabatan', $karyawan->jabatan) }}" placeholder="contoh: Tata Usaha, Satpam, Pustakawan">
                </div>
                <div class="form-group col-md-6">
                    <label>Status Kepegawaian</label>
                    <input type="text" name="status" class="form-control" value="{{ old('status', $karyawan->status) }}" placeholder="contoh: PNS, Honorer, Kontrak">
                </div>
                <div class="form-group col-md-6">
                    <label>Telepon</label>
                    <input type="text" name="telepon" class="form-control" value="{{ old('telepon', $karyawan->telepon) }}">
                </div>
                <div class="form-group col-12">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $karyawan->alamat) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.karyawan.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
