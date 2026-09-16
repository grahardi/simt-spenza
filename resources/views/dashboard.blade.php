@extends('layouts.app')

@section('title', 'Depan')

@php
    $member = auth('member')->user();
    $panels = \App\Services\MenuPanelDefinisi::semua();
@endphp

@section('content')
<div class="p-4 bg-white rounded shadow mb-4">
    <h1 class="h5 mb-1">Selamat datang, {{ $member->nama }}</h1>
    <p class="text-muted mb-0">Peran: {{ implode(', ', $member->roles()) ?: '-' }}</p>
</div>

@if ($member->hasRole('superadmin'))
    <div class="p-4 rounded shadow mb-4 text-white" style="background:linear-gradient(135deg,#1a1030,#4b0082);">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3">
            <div>
                <h3 class="h6 mb-1"><i class="fas fa-user-shield me-2"></i>Panel Superadmin</h3>
                <p class="mb-0 small opacity-75">Kelola data siswa, guru & roles, absensi, pelanggaran, dan bimbingan konseling secara penuh.</p>
            </div>
            <a href="{{ route('superadmin.dashboard') }}" class="btn btn-light btn-sm">
                <i class="fas fa-arrow-right me-1"></i> Buka Panel Superadmin
            </a>
        </div>
    </div>
@endif

@foreach ($panels as $role => $panel)
    @if ($member->hasRole($role))
        @php
            $itemAktif = array_values(array_filter($panel['items'], fn ($item) => \App\Models\PengaturanFitur::aktifUntuk($role, $item['label'])));
        @endphp
        @if (!empty($itemAktif))
            <x-menu-section :title="$panel['title']" :items="$itemAktif" :theme="$panel['theme']" />
        @endif
    @endif
@endforeach
@endsection
