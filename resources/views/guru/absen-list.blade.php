@extends('layouts.app')

@section('title', 'Absen Guru')

@php $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple']; @endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Absen Guru Hari Ini</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada guru yang tercatat absen hari ini.
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @foreach ($daftar as $i => $d)
                <div class="border rounded">
                    <div role="button" data-bs-toggle="collapse" data-bs-target="#guruDetail{{ $i }}" class="d-flex justify-content-between align-items-center p-3" style="cursor:pointer;">
                        <div>
                            <strong>{{ $d->guru->nama }}</strong>
                            <span class="badge-status badge-{{ $d->absen->status }} ms-2">{{ $d->absen->labelStatus() }}</span>
                            @if ($d->absen->keterangan)
                                <span class="text-muted small ms-1">{{ $d->absen->keterangan }}</span>
                            @endif
                        </div>
                        <span class="text-muted small"><i class="fas fa-chevron-down me-1"></i> Jadwal &amp; Tugas</span>
                    </div>
                    <div class="collapse" id="guruDetail{{ $i }}">
                        <div class="p-3 border-top bg-light">
                            @if ($d->jadwal->isEmpty())
                                <p class="text-muted small mb-0">Tidak ada jadwal mengajar untuk guru ini hari ini.</p>
                            @else
                                <div class="d-flex flex-column gap-2">
                                    @php $warnaIndex = -1; $kunciSebelumnya = null; @endphp
                                    @foreach ($d->jadwal->sortBy('jamhari') as $j)
                                        @php
                                            $kunciSekarang = $j->kelas.'|'.$j->mapel;
                                            $blokBaru = $kunciSekarang !== $kunciSebelumnya;
                                            if ($blokBaru) { $warnaIndex++; }
                                            $kunciSebelumnya = $kunciSekarang;
                                            $warna = $palet[$warnaIndex % count($palet)];
                                            $tampilkanTombolTugas = $blokBaru && $d->absen->status !== 'a';
                                            $tugasSudahAda = $tampilkanTombolTugas ? ($d->tugas[$j->kelas] ?? null) : null;
                                        @endphp
                                        <div class="jadwal-baris bg-{{ $warna }}">
                                            <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                                            <span class="jadwal-waktu-kecil">{{ $j->waktu ?? '-' }}</span>
                                            <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                                            <span class="jadwal-mapel-kecil">{{ $j->mapelLengkap() }}</span>
                                            @if ($tampilkanTombolTugas)
                                                <a href="{{ route('tugas.upload', [$d->guru, $j->kelas]) }}" class="btn btn-sm btn-outline-dark" style="border-color:currentColor;color:inherit;">
                                                    @if ($tugasSudahAda)
                                                        <i class="fas fa-eye me-1"></i> Lihat Tugas
                                                    @else
                                                        <i class="fas fa-clipboard-list me-1"></i> Upload Tugas
                                                    @endif
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <a href="{{ route('jadwal.guru', $d->guru) }}" class="btn btn-sm btn-outline-secondary mt-3">
                                <i class="fas fa-calendar-alt me-1"></i> Lihat Halaman Lengkap Guru Ini
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
