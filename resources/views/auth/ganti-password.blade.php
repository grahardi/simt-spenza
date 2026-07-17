@extends('layouts.app')

@section('title', 'Ganti Password')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-key me-2"></i>Ganti Password</h1>
</div>

@if ($wajib)
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle me-1"></i>
        Demi keamanan (situs ini sekarang bisa diakses publik), Bapak/Ibu <strong>wajib ganti password</strong>
        dulu sebelum bisa memakai menu lain.
    </div>
@endif

@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="p-4 bg-white rounded shadow" style="max-width:420px;">
    <form method="POST" action="{{ route('ganti-password.simpan') }}">
        @csrf
        <div class="mb-3">
            <label class="form-label">Password Baru (minimal 6 karakter)</label>
            <input type="password" name="password_baru" class="form-control" minlength="6" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Ulangi Password Baru</label>
            <input type="password" name="password_baru_confirmation" class="form-control" minlength="6" required>
        </div>
        <button type="submit" class="btn btn-primary w-100"><i class="fas fa-save me-1"></i> Simpan Password Baru</button>
    </form>
</div>
@endsection
