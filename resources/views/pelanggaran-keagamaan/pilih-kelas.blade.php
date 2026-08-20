@extends('layouts.app')

@section('title', 'Pelanggaran Keagamaan - Pilih Kelas')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-mosque me-2"></i>Pelanggaran Keagamaan - Pilih Kelas</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    <div class="kelas-grid">
        @foreach ($daftarKelas as $k)
            @php
                $tingkat = trim(explode('-', $k)[0] ?? '');
                $warna = match (true) {
                    str_starts_with($tingkat, '7') => 'kelas-7',
                    str_starts_with($tingkat, '8') => 'kelas-8',
                    str_starts_with($tingkat, '9') => 'kelas-9',
                    default => 'kelas-lain',
                };
            @endphp
            <a href="{{ route('pelanggaran-keagamaan.form-kelas', $k) }}" class="kelas-btn {{ $warna }}">
                {{ str_replace(' - ', '', $k) }}
            </a>
        @endforeach
    </div>
</div>
@endsection
