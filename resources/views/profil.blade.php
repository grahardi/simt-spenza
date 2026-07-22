@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-user-circle me-2"></i>Profil Saya</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:560px;">
    <table class="table table-sm mb-4">
        <tr><th style="width:160px">Nomor ID</th><td>{{ $member->user }}</td></tr>
        <tr><th>Jabatan Login</th><td>{{ $member->jabatan ?: '-' }}</td></tr>
        <tr><th>Peran</th><td>{{ implode(', ', $member->roles()) ?: '-' }}</td></tr>
    </table>

    <form method="POST" action="{{ route('profil.update') }}">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" name="nama" class="form-control" value="{{ old('nama', $member->nama) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">NIP</label>
            <input type="text" name="nip" class="form-control" value="{{ old('nip', $member->dataGuru->nip ?? '') }}" placeholder="{{ $member->dataGuru ? '' : 'Akun ini tidak terhubung ke data guru' }}" {{ $member->dataGuru ? '' : 'disabled' }}>
        </div>
        <div class="mb-3">
            <label class="form-label">Pangkat/Golongan</label>
            <input type="text" name="pangkat" class="form-control" value="{{ old('pangkat', $member->pangkat) }}" placeholder="contoh: Pembina Utama Muda">
        </div>
        <div class="mb-3">
            <label class="form-label">Jabatan Dinas</label>
            <input type="text" name="jabatan_dinas" class="form-control" value="{{ old('jabatan_dinas', $member->jabatan_dinas) }}" placeholder="contoh: Guru Mata Pelajaran Informatika">
        </div>

        <hr>
        <p class="text-muted small">Kosongkan bagian password kalau tidak ingin menggantinya.</p>
        <div class="mb-3">
            <label class="form-label">Password Baru</label>
            <input type="password" name="password_baru" class="form-control">
        </div>
        <div class="mb-3">
            <label class="form-label">Ulangi Password Baru</label>
            <input type="password" name="password_baru_confirmation" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
    </form>
</div>
@endsection
