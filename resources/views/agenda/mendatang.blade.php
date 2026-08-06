@extends('layouts.app')

@section('title', 'Agenda Mendatang')

@php $bisaEdit = auth('member')->user()->hasRole('admin_kegiatan') || auth('member')->user()->hasRole('kesiswaan'); @endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-arrow-right fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Agenda Mendatang</h1>
    </div>
    <a href="{{ route('agenda.index') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-calendar me-1"></i> Lihat Kalender
    </a>
</div>

@if ($agenda->isEmpty())
    <div class="bg-white rounded shadow text-muted text-center py-4">
        <i class="far fa-question-circle me-1"></i> Belum ada agenda kegiatan mendatang.
    </div>
@else
    @php
        $warnaKategori = ['libur' => 'red', 'kbm' => 'blue', 'ujian' => 'amber', 'kegiatan' => 'green'];
    @endphp
    <div class="menu-grid">
        @foreach ($agenda as $a)
            <a href="{{ $bisaEdit ? route('agenda.edit', $a) : '#' }}" class="menu-card bg-{{ $warnaKategori[$a->kategori] ?? 'purple' }}" @if (!$bisaEdit) style="cursor:default;" onclick="return false;" @endif>
                <span class="menu-icon">
                    <i class="fas fa-{{ $bisaEdit ? 'edit' : 'calendar-day' }}"></i>
                </span>
                <span class="menu-title">{{ $a->judul }}</span>
                <span class="d-block small mt-1 opacity-75">
                    {{ $a->tanggal_mulai->translatedFormat('d M Y') }}
                    @if ($a->tanggal_selesai && !$a->tanggal_selesai->isSameDay($a->tanggal_mulai))
                        s/d {{ $a->tanggal_selesai->translatedFormat('d M Y') }}
                    @endif
                </span>
                @if ($a->ketua() || $a->sekretaris())
                    <span class="d-block small mt-1 opacity-75">
                        @if ($a->ketua()) Ketua: {{ $a->ketua()->guru->nama ?? '-' }} @endif
                        @if ($a->sekretaris()) &middot; Sekretaris: {{ $a->sekretaris()->guru->nama ?? '-' }} @endif
                    </span>
                @endif
                @if ($a->keterangan)
                    <span class="d-block small mt-1">{{ $a->keterangan }}</span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="mt-3">
        {{ $agenda->onEachSide(1)->links() }}
    </div>
@endif
@endsection
