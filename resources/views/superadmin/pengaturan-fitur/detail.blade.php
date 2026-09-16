@extends('layouts.adminlte')

@section('title', 'Pengaturan Fitur - ' . $role)

@include('partials.definisi-menu-panel')

@section('content')
@php
    $panel = $panels[$role] ?? null;
    $sudahNonaktif = \App\Models\PengaturanFitur::where('role', $role)->where('aktif', false)->pluck('fitur_key')->all();
@endphp

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Pengaturan Fitur - {{ $panel['title'] ?? $role }}</h3>
    </div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        @if (!$panel)
            <p class="text-muted">Role ini tidak ditemukan di daftar menu.</p>
        @else
            <form method="POST" action="{{ route('superadmin.pengaturan-fitur.simpan', $role) }}">
                @csrf
                <div class="row">
                    @foreach ($panel['items'] as $item)
                        @php
                            $key = \App\Models\PengaturanFitur::keyDariLabel($item['label']);
                            $aktif = !in_array($key, $sudahNonaktif, true);
                        @endphp
                        <div class="col-md-4 col-sm-6 mb-2">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="fitur-{{ $key }}" name="fitur[]" value="{{ $key }}" @checked($aktif)>
                                <label class="custom-control-label" for="fitur-{{ $key }}">
                                    <i class="{{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                                </label>
                            </div>
                            <input type="hidden" name="semua_fitur_key[]" value="{{ $key }}">
                        </div>
                    @endforeach
                </div>

                <button type="submit" class="btn btn-primary mt-3"><i class="fas fa-save me-1"></i> Simpan</button>
                <a href="{{ route('superadmin.pengaturan-fitur.index') }}" class="btn btn-outline-secondary mt-3">Kembali</a>
            </form>
        @endif
    </div>
</div>
@endsection
