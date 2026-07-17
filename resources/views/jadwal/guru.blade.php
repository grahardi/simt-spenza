@extends('layouts.app')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-clock me-2"></i>
        Jadwal Mengajar {{ $guru->nama ?? '' }} - {{ ucfirst(strtolower($hari)) }}
    </h1>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($jadwal->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada jadwal mengajar hari {{ ucfirst(strtolower($hari)) }}.
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach ($jadwal as $j)
                @php
                    $parts = $j->waktu ? array_map('trim', explode('-', $j->waktu)) : [];
                    $mulai = $parts[0] ?? null;
                    $selesai = $parts[1] ?? null;
                    $sedangBerlangsung = $mulai && $selesai && $sekarang >= $mulai && $sekarang <= $selesai;
                @endphp
                <div class="jadwal-baris {{ $sedangBerlangsung ? 'jadwal-baris-aktif' : '' }}">
                    <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                    <span class="jadwal-waktu-kecil">{{ $j->waktu ?? '-' }}</span>
                    <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                    <span class="jadwal-mapel-kecil">{{ $j->mapel }}</span>
                    @if ($sedangBerlangsung)
                        <span class="jadwal-live-dot" title="Sedang berlangsung"></span>
                    @endif
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
