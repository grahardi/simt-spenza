@extends('layouts.adminlte')

@section('title', $kelas->exists ? 'Edit Kelas' : 'Tambah Kelas')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">{{ $kelas->exists ? 'Edit Kelas' : 'Tambah Kelas' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ $kelas->exists ? route('superadmin.kelas.update', $kelas) : route('superadmin.kelas.store') }}">
            @csrf
            @if ($kelas->exists) @method('PUT') @endif
            <div class="form-group">
                <label>Nama Kelas</label>
                <input type="text" name="nama_kelas" class="form-control" value="{{ old('nama_kelas', $kelas->nama_kelas) }}" placeholder="contoh: 7 - A" required>
            </div>
            <div class="form-group">
                <label>Kapasitas (jumlah siswa)</label>
                <input type="number" name="jumlah" class="form-control" value="{{ old('jumlah', $kelas->jumlah) }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.kelas.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
