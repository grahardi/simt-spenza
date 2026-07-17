@extends('layouts.app')

@section('title', 'Jadwal Kelas ' . $kelas)

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
            <h3 class="h6 text-uppercase text-muted mt-4 mb-2">{{ $hari }}</h3>
            <div class="table-responsive mb-2">
                <table class="table table-sm table-striped align-middle">
                    <thead>
                        <tr>
                            <th style="width:70px">Jam</th>
                            <th>Mapel</th>
                            <th>Guru</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($jadwalPerHari[$hari]->sortBy('jamhari') as $j)
                            <tr>
                                <td>{{ $j->jamhari }}</td>
                                <td>{{ $j->mapel }}</td>
                                <td>{{ $j->namaGuru() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach
    @endif
</div>
@endsection
