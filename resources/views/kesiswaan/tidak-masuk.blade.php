@extends('layouts.app')

@section('title', 'Siswa Tidak Masuk 3+ Hari')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-user-clock fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Siswa Tidak Masuk 3+ Hari</h1>
    </div>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0">Minggu berjalan (pilih tanggal berapa saja di minggu itu)</label>
        <input type="date" name="minggu" class="form-control" style="max-width:200px" value="{{ request('minggu', $awalMinggu->format('Y-m-d')) }}" onchange="this.form.submit()">
    </form>
    <p class="text-muted small mt-2 mb-0">
        Menampilkan periode <strong>{{ $awalMinggu->translatedFormat('d F Y') }}</strong> s/d <strong>{{ $akhirMinggu->translatedFormat('d F Y') }}</strong>.
    </p>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa yang Alfa 3 hari atau lebih di minggu ini.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Kelas</th><th>Jumlah Alfa Minggu Ini</th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $i => $d)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->siswa->kelas ?? '-' }}</td>
                        <td><span class="badge bg-danger">{{ $d->jumlah_alfa }} hari</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
