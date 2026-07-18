@extends('layouts.adminlte')

@section('title', $siswa->exists ? 'Edit Siswa' : 'Tambah Siswa')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">{{ $siswa->exists ? 'Edit Siswa' : 'Tambah Siswa' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ $siswa->exists ? route('superadmin.siswa.update', $siswa) : route('superadmin.siswa.store') }}">
            @csrf
            @if ($siswa->exists) @method('PUT') @endif

            <div class="row">
                <div class="form-group col-md-4">
                    <label>NISN</label>
                    <input type="text" name="nisn" class="form-control" value="{{ old('nisn', $siswa->nisn) }}">
                </div>
                <div class="form-group col-md-4">
                    <label>Kelas</label>
                    <select name="kelas" class="form-control" required>
                        @foreach ($daftarKelas as $k)
                            <option value="{{ $k }}" @selected(old('kelas', $siswa->kelas) === $k)>{{ $k }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group col-md-4">
                    <label>Jenis Kelamin</label>
                    <select name="jenis_kelamin" class="form-control" required>
                        <option value="L" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'L')>Laki-laki</option>
                        <option value="P" @selected(old('jenis_kelamin', $siswa->jenis_kelamin) === 'P')>Perempuan</option>
                    </select>
                </div>
                <div class="form-group col-md-8">
                    <label>Nama Lengkap</label>
                    <input type="text" name="nama_lengkap" class="form-control" value="{{ old('nama_lengkap', $siswa->nama_lengkap) }}" required>
                </div>
                <div class="form-group col-md-4">
                    <label>Nomor Bangku</label>
                    <input type="number" name="nomer_bangku" class="form-control" value="{{ old('nomer_bangku', $siswa->nomer_bangku) }}">
                </div>
                <div class="form-group col-md-6">
                    <label>WhatsApp</label>
                    <p class="text-muted small mb-0">
                        Kelola nomor WA (bisa lebih dari 1: Ayah/Ibu/Wali) di menu
                        <a href="{{ route('superadmin.whatsapp-nomor.index') }}">Nomor WA Terdaftar</a>.
                    </p>
                </div>
                <div class="form-group col-md-6">
                    <label>Email / TTL</label>
                    <input type="text" name="email" class="form-control" value="{{ old('email', $siswa->email) }}">
                </div>
                <div class="form-group col-12">
                    <label>Alamat</label>
                    <textarea name="alamat" class="form-control" rows="2">{{ old('alamat', $siswa->alamat) }}</textarea>
                </div>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
