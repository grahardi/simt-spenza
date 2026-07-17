@extends('layouts.app')

@section('title', 'Jadwal - ' . $guru->nama)

@php
    $hariIni = \App\Models\Member::namaHariJakartaHuruBesar();
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route('jadwal.pilih-guru') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Ganti Guru
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($jadwalPerHari->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada jadwal mengajar untuk {{ $guru->nama }}.
        </div>
    @else
        @foreach ($urutanHari as $hari)
            @continue(!isset($jadwalPerHari[$hari]))
            <h3 class="h6 text-uppercase text-muted mt-4 mb-2">
                {{ $hari }}
                @if ($hari === $hariIni) <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Hari ini</span> @endif
            </h3>
            <div class="table-responsive mb-2">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                        <tr>
                            <th style="width:70px">Jam</th>
                            <th>Kelas</th>
                            <th>Mapel</th>
                            @if ($hari === $hariIni)
                                <th></th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwalPerHari[$hari]->sortBy('jamhari') as $j)
                            <tr>
                                <td>{{ $j->jamhari }}</td>
                                <td>{{ $j->kelas }}</td>
                                <td>{{ $j->mapel }}</td>
                                @if ($hari === $hariIni)
                                    <td class="text-end">
                                        <a href="{{ route('tugas.upload', [$guru, $j->kelas]) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-clipboard-list me-1"></i> Upload Tugas
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
