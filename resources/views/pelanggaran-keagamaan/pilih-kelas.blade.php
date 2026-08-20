@extends('layouts.app')

@section('title', 'Pelanggaran Keagamaan - Pilih Kelas')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-mosque me-2"></i>Pelanggaran Keagamaan - Pilih Kelas</h1>
</div>

@php $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple', 'red']; @endphp

<div class="menu-grid">
    @foreach ($daftarKelas as $i => $k)
        <a href="{{ route('pelanggaran-keagamaan.form-kelas', $k) }}" class="menu-card bg-{{ $palet[$i % count($palet)] }}">
            <span class="menu-icon"><i class="fas fa-users"></i></span>
            <span class="menu-title">{{ $k }}</span>
        </a>
    @endforeach
</div>
@endsection
