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
        <div class="jadwal-timeline">
            @foreach ($jadwal as $j)
                @php
                    $parts = $j->waktu ? array_map('trim', explode('-', $j->waktu)) : [];
                    $mulai = $parts[0] ?? null;
                    $selesai = $parts[1] ?? null;
                    $sedangBerlangsung = $mulai && $selesai && $sekarang >= $mulai && $sekarang <= $selesai;
                @endphp
                <div class="jadwal-item {{ $sedangBerlangsung ? 'jadwal-item-aktif' : '' }}">
                    <div class="jadwal-jam">
                        <span class="jadwal-jam-angka">{{ $j->jamhari }}</span>
                        <span class="jadwal-jam-label">Jam ke</span>
                    </div>
                    <div class="jadwal-garis"></div>
                    <div class="jadwal-detail">
                        <div class="d-flex align-items-center justify-content-between flex-wrap gap-1">
                            <span class="fw-bold">{{ $j->kelas }}</span>
                            @if ($j->waktu)
                                <span class="text-muted small"><i class="far fa-clock me-1"></i>{{ $j->waktu }}</span>
                            @endif
                        </div>
                        <div class="text-muted">{{ $j->mapel }}</div>
                        @if ($sedangBerlangsung)
                            <span class="badge-status mt-1" style="background:#eaf3de;color:#3b6d11;">
                                <i class="fas fa-circle me-1" style="font-size:8px;"></i> Sedang berlangsung
                            </span>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
