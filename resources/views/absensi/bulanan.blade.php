@extends('layouts.app')

@section('title', 'Absensi Bulanan')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-calendar-alt me-2"></i>Rekap Absensi Bulanan</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Bulan</label>
        <input type="month" name="bulan" class="form-control" style="max-width:200px"
               value="{{ $bulan->format('Y-m') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h6 text-muted mb-3">{{ $bulan->translatedFormat('F Y') }}</h3>

    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada data absensi di bulan ini.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>Kelas</th>
                        <th>Sakit</th>
                        <th>Ijin</th>
                        <th>Alfa</th>
                        <th>Dispensasi</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($rekap->sortKeys() as $kelas => $baris)
                        @php
                            $s = $baris->firstWhere('keterangan', 's')->jumlah ?? 0;
                            $i = $baris->firstWhere('keterangan', 'i')->jumlah ?? 0;
                            $a = $baris->firstWhere('keterangan', 'a')->jumlah ?? 0;
                            $d = $baris->firstWhere('keterangan', 'd')->jumlah ?? 0;
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $kelas }}</td>
                            <td>{{ $s }}</td>
                            <td>{{ $i }}</td>
                            <td>{{ $a }}</td>
                            <td>{{ $d }}</td>
                            <td class="fw-bold">{{ $s + $i + $a + $d }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
