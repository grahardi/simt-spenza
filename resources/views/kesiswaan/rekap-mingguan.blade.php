@extends('layouts.app')

@section('title', 'Rekap Absen Mingguan')

@section('content')
@include('partials.menu-absensi')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-calendar-week fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Rekap Absen Mingguan</h1>
    </div>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0">Minggu berjalan (pilih tanggal berapa saja di minggu itu)</label>
        <input type="date" name="minggu" class="form-control" style="max-width:200px" value="{{ request('minggu', $awalMinggu->format('Y-m-d')) }}" onchange="this.form.submit()">
    </form>
    <p class="text-muted small mt-2 mb-0">
        Periode <strong>{{ $awalMinggu->translatedFormat('d F Y') }}</strong> s/d <strong>{{ $akhirMinggu->translatedFormat('d F Y') }}</strong>.
        Kolom S/I/A = Sakit/Ijin/Alfa (Dispensasi tidak dihitung sebagai tidak masuk).
        Baris dengan total 3 atau lebih ditandai warna kuning.
    </p>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa yang Sakit/Ijin/Alfa di minggu ini.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-bordered mb-0 align-middle">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Kelas</th>
                    <th class="text-center" style="width:60px">S</th>
                    <th class="text-center" style="width:60px">I</th>
                    <th class="text-center" style="width:60px">A</th>
                    <th class="text-center" style="width:80px">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($rekap as $r)
                    @php $total = $r->sakit + $r->ijin + $r->alfa; @endphp
                    <tr style="{{ $total >= 3 ? 'background:#fff3cd;' : '' }}">
                        <td>{{ $r->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $r->siswa->kelas ?? '-' }}</td>
                        <td class="text-center">{{ $r->sakit }}</td>
                        <td class="text-center">{{ $r->ijin }}</td>
                        <td class="text-center">{{ $r->alfa }}</td>
                        <td class="text-center fw-bold">
                            {{ $total }}
                            @if ($total >= 3)
                                <i class="fas fa-exclamation-triangle text-warning ms-1" title="Perlu perhatian"></i>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
