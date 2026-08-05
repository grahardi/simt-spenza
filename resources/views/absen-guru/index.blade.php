@extends('layouts.app')

@section('title', 'Absen Guru')

@php $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple']; @endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Absen Guru Hari Ini</h1>
    </div>
    <a href="{{ route('absen-guru.pilih-guru') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Ajuan Manual
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow mb-3">
    <h6 class="mb-3"><i class="fas fa-hourglass-half text-warning me-1"></i> Menunggu ACC ({{ $menungguAcc->count() }})</h6>
    @if ($menungguAcc->isEmpty())
        <p class="text-muted small mb-0">Tidak ada ajuan yang menunggu ACC.</p>
    @else
        @foreach ($menungguAcc as $i => $d)
            <div class="border rounded mb-2" style="border-color:#ffc107 !important;">
                <div role="button" data-bs-toggle="collapse" data-bs-target="#detailP{{ $i }}" class="d-flex justify-content-between align-items-center p-3 flex-wrap gap-2" style="cursor:pointer;">
                    <div>
                        <strong>{{ $d->guru->nama }}</strong>
                        <span class="badge-status badge-{{ $d->record->status }} ms-2">{{ $d->record->labelStatus() }}</span>
                        @if ($d->record->keterangan)
                            <span class="text-muted small ms-1">{{ $d->record->keterangan }}</span>
                        @endif
                        @if ($d->record->foto)
                            <a href="{{ Storage::url($d->record->foto) }}" target="_blank" class="small ms-1"><i class="fas fa-image"></i> Foto</a>
                        @endif
                    </div>
                    <div class="d-flex gap-2" onclick="event.stopPropagation();">
                        <form method="POST" action="{{ route('absen-guru.acc', $d->record) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i> ACC</button>
                        </form>
                        <form method="POST" action="{{ route('absen-guru.tolak', $d->record) }}" class="d-inline" onsubmit="return confirm('Tolak ajuan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-times me-1"></i> Tolak</button>
                        </form>
                    </div>
                </div>
                <div class="collapse" id="detailP{{ $i }}">
                    <div class="p-3 border-top bg-light">
                        @include('absen-guru._jadwal-tugas', ['d' => $d, 'palet' => $palet])
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<div class="p-4 bg-white rounded shadow">
    <h6 class="mb-3"><i class="fas fa-check-circle text-success me-1"></i> Sudah Diacc ({{ $sudahDiacc->count() }})</h6>
    @if ($sudahDiacc->isEmpty())
        <p class="text-muted small mb-0">Tidak ada guru yang tercatat absen hari ini.</p>
    @else
        @foreach ($sudahDiacc as $i => $d)
            <div class="border rounded mb-2">
                <div role="button" data-bs-toggle="collapse" data-bs-target="#detailA{{ $i }}" class="d-flex justify-content-between align-items-center p-3" style="cursor:pointer;">
                    <div>
                        <strong>{{ $d->guru->nama }}</strong>
                        <span class="badge-status badge-{{ $d->record->status }} ms-2">
                            {{ match($d->record->status){'s'=>'Sakit','i'=>'Ijin','a'=>'Alfa','d'=>'Dispensasi',default=>$d->record->status} }}
                        </span>
                        @if ($d->record->keterangan)
                            <span class="text-muted small ms-1">{{ $d->record->keterangan }}</span>
                        @endif
                    </div>
                    <span class="text-muted small"><i class="fas fa-chevron-down me-1"></i> Jadwal &amp; Tugas</span>
                </div>
                <div class="collapse" id="detailA{{ $i }}">
                    <div class="p-3 border-top bg-light">
                        @include('absen-guru._jadwal-tugas', ['d' => $d, 'palet' => $palet])
                    </div>
                </div>
            </div>
        @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
