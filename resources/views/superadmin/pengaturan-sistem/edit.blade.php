@extends('layouts.adminlte')

@section('title', 'Pengaturan Sistem')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Pengaturan Sistem</h3></div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ route('superadmin.pengaturan-sistem.update') }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Nomor WhatsApp Kepala Sekolah</label>
                <input type="text" name="nomor_wa_kepsek" class="form-control" value="{{ old('nomor_wa_kepsek', $pengaturan->nomor_wa_kepsek) }}" placeholder="contoh: 6281234567890">
                <small class="form-text text-muted">
                    Format internasional tanpa tanda "+" (contoh: 6281234567890). Nomor ini akan otomatis menerima
                    notifikasi WhatsApp setiap ada guru yang tercatat Sakit/Ijin/Dispensasi (baik dicatat manual oleh
                    piket maupun lewat ajuan guru sendiri).
                </small>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Simpan</button>
        </form>
    </div>
</div>
@endsection
