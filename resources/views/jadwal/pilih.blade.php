@extends($layout ?? 'layouts.app')

@php $prefix = request()->routeIs('jadwal-publik.*') ? 'jadwal-publik.' : 'jadwal.'; @endphp

@section('title', 'Jadwal Pelajaran')

@section('content')
<div class="alert alert-danger py-2 mb-3"><i class="fas fa-exclamation-triangle me-1"></i> Ada beberapa jadwal yang masih perbaikan. Harap ditunggu.</div>
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-calendar-alt me-2"></i>Jadwal Pelajaran</h1>
</div>

<div class="menu-grid" style="grid-template-columns: repeat(2, 1fr);">
    <a href="{{ route($prefix.'kelas-grid') }}" class="menu-card bg-blue" style="padding:28px 10px;">
        <span class="menu-icon" style="width:64px;height:64px;font-size:28px;">
            <i class="fas fa-chalkboard"></i>
        </span>
        <span class="menu-title" style="font-size:15px;">Pilih Kelas</span>
    </a>
    <a href="{{ route($prefix.'pilih-guru') }}" class="menu-card bg-teal" style="padding:28px 10px;">
        <span class="menu-icon" style="width:64px;height:64px;font-size:28px;">
            <i class="fas fa-chalkboard-teacher"></i>
        </span>
        <span class="menu-title" style="font-size:15px;">Pilih Guru</span>
    </a>
</div>
@endsection
