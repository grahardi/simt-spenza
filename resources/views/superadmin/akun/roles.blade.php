@extends('layouts.adminlte')

@section('title', 'Kelola Akun - ' . $member->nama)

@section('content')
<div class="card mb-3">
    <div class="card-header"><h3 class="card-title">Roles - {{ $member->nama }} (Login: {{ $member->user }})</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.akun.update', $member) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Jabatan Login <span class="text-muted small">(isi "Superadmin" untuk akses penuh panel ini)</span></label>
                <input type="text" name="jabatan" class="form-control" value="{{ $member->jabatan }}">
            </div>
            <div class="form-group">
                <label>Wali Kelas <span class="text-muted small">(isi nama kelas persis, mis. "7 - A")</span></label>
                <input type="text" name="walikelas" class="form-control" value="{{ $member->walikelas }}">
            </div>
            <div class="form-group">
                <label>Piket <span class="text-muted small">("1" = setiap hari, atau nama hari huruf besar mis. "SENIN")</span></label>
                <input type="text" name="piket" class="form-control" value="{{ $member->piket }}">
            </div>

            <label class="d-block mb-2">Roles Lainnya</label>
            <div class="row mb-3">
                @foreach ([
                    'admin' => 'Admin Absensi',
                    'tatib' => 'Tata Tertib',
                    'bk' => 'Bimbingan Konseling',
                    'guru' => 'Guru (menu jabatan guru)',
                    'keagamaan' => 'Keagamaan',
                    'kebersihan' => 'Kebersihan',
                    'kepsek' => 'Kepala Sekolah',
                    'adminsoal' => 'Admin Soal',
                    'tata_usaha' => 'Tata Usaha',
                    'uks' => 'UKS',
                ] as $flag => $label)
                    <div class="col-md-3 mb-2">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="r_{{ $flag }}" name="{{ $flag }}" value="1" {{ $member->{$flag} ? 'checked' : '' }}>
                            <label class="custom-control-label" for="r_{{ $flag }}">{{ $label }}</label>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Roles</button>
            <a href="{{ route('superadmin.akun.index') }}" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>

<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Reset Password</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.akun.reset-password', $member) }}">
            @csrf
            <div class="form-group">
                <label>Password Baru</label>
                <input type="password" name="password_baru" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning"><i class="fas fa-key me-1"></i> Reset Password</button>
        </form>
    </div>
</div>
@endsection
