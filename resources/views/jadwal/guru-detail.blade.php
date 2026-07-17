@extends($layout ?? 'layouts.app')

@php $prefix = request()->routeIs('jadwal-publik.*') ? 'jadwal-publik.' : 'jadwal.'; @endphp

@section('title', 'Jadwal - ' . $guru->nama)

@php
    $hariIni = \App\Models\Member::namaHariJakartaHuruBesar();
    $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple'];
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route($prefix.'pilih-guru') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
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

            <div class="d-flex flex-column gap-2 mb-2">
                @foreach ($jadwalPerHari[$hari]->sortBy('jamhari') as $i => $j)
                    @php $warna = $palet[$i % count($palet)]; @endphp
                    <div class="jadwal-baris bg-{{ $warna }}">
                        <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                        <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                        <span class="jadwal-mapel-kecil">{{ $j->mapelLengkap() }}</span>
                        @if ($hari === $hariIni && $prefix === 'jadwal.')
                            <a href="{{ route('tugas.upload', [$guru, $j->kelas]) }}" class="btn btn-sm btn-outline-dark" style="border-color:currentColor;color:inherit;">
                                <i class="fas fa-clipboard-list me-1"></i> Upload Tugas
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>
@endsection
