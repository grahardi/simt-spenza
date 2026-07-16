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
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Jam Ke</th>
                        <th>Kelas</th>
                        <th>Mapel</th>
                        <th>Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($jadwal as $j)
                        <tr>
                            <td>{{ $j->jamhari }}</td>
                            <td>{{ $j->kelas }}</td>
                            <td>{{ $j->mapel }}</td>
                            <td>{{ $j->waktu }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
