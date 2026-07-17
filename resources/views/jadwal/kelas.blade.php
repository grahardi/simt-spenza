@extends('layouts.app')

@section('title', 'Jadwal Kelas ' . $kelas)

@php
    $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple'];
    $hariIni = \App\Models\Member::namaHariJakartaHuruBesar();
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal Kelas {{ $kelas }}</h1>
    </div>
    <a href="{{ route('jadwal.kelas-grid') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Ganti Kelas
    </a>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($jadwalPerHari->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada jadwal untuk kelas {{ $kelas }}.
        </div>
    @else
        @foreach ($urutanHari as $hari)
            @continue(!isset($jadwalPerHari[$hari]))
            <h3 class="h6 text-uppercase text-muted mt-4 mb-2">
                {{ $hari }}
                @if ($hari === $hariIni) <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Hari ini</span> @endif
            </h3>

            <div class="d-flex flex-column gap-2 mb-2">
                @foreach ($jadwalPerHari[$hari]->sortBy('jamhari') as $i => $j)
                    @php $warna = $palet[$i % count($palet)]; @endphp
                    <div class="jadwal-baris bg-{{ $warna }}">
                        <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                        <span class="jadwal-mapel-kecil" style="flex:1;">{{ $j->mapelLengkap() }}</span>
                        <span class="jadwal-mapel-kecil" style="flex:1;">{{ $j->namaGuru() }}</span>
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>
@endsection
