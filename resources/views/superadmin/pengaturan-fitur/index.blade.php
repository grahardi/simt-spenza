@extends('layouts.adminlte')

@section('title', 'Pengaturan Fitur')

@php $panels = \App\Services\MenuPanelDefinisi::semua(); @endphp

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Pengaturan Fitur per Role</h3></div>
    <div class="card-body">
        <div class="alert alert-info">
            Klik salah satu role di bawah untuk atur menu/fitur apa saja yang tampil untuknya.
            Fitur yang tidak dicentang akan disembunyikan (tidak dihapus/hardcode - tinggal centang lagi kalau mau diaktifkan ulang).
        </div>

        <div class="row">
            @foreach ($panels as $role => $panel)
                <div class="col-md-3 col-sm-4 col-6 mb-3">
                    <a href="{{ route('superadmin.pengaturan-fitur.detail', $role) }}" class="text-decoration-none">
                        <div class="card text-center h-100" style="cursor:pointer;">
                            <div class="card-body">
                                <i class="fas fa-layer-group fa-2x mb-2 text-primary"></i>
                                <p class="mb-0 fw-bold">{{ $panel['title'] }}</p>
                                <small class="text-muted">{{ count($panel['items']) }} fitur</small>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
