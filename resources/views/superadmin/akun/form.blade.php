@extends('layouts.adminlte')

@section('title', 'Buat Akun')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Buat Akun Login Baru</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.akun.store') }}">
            @csrf
            <div class="form-group">
                <label>Nomor ID Login</label>
                <input type="text" name="user" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Nama</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label>Hubungkan ke Data Guru (opsional)</label>
                <select name="id_guru" class="form-control">
                    <option value="">- Tidak terhubung guru manapun -</option>
                    @foreach ($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Atau hubungkan ke Data Karyawan (Tata Usaha, dll - opsional)</label>
                <select name="id_karyawan" class="form-control">
                    <option value="">- Tidak terhubung karyawan manapun -</option>
                    @foreach ($daftarKaryawan as $k)
                        <option value="{{ $k->id_karyawan }}">{{ $k->nama }} @if($k->jabatan) ({{ $k->jabatan }}) @endif</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-user-plus me-1"></i> Buat Akun</button>
            <a href="{{ route('superadmin.akun.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
