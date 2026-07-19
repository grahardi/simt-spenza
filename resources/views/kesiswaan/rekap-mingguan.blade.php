@extends('layouts.app')

@section('title', 'Rekap Absen Mingguan')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-calendar-week fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Rekap Absen Mingguan</h1>
    </div>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada data absensi.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th class="text-center">Sakit</th>
                    <th class="text-center">Ijin</th>
                    <th class="text-center">Alfa</th>
                    <th class="text-center">Dispensasi</th>
                    <th class="text-center">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $r)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($r->tgl_absen)->translatedFormat('l, d F Y') }}</td>
                        <td class="text-center">{{ $r->sakit }}</td>
                        <td class="text-center">{{ $r->ijin }}</td>
                        <td class="text-center">{{ $r->alfa }}</td>
                        <td class="text-center">{{ $r->dispensasi }}</td>
                        <td class="text-center fw-bold">{{ $r->sakit + $r->ijin + $r->alfa + $r->dispensasi }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>

        {{ $rekap->onEachSide(1)->links() }}
    @endif
</div>
@endsection
