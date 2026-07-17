@extends('layouts.app')

@section('title', 'Aktivitas Kelas')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-people-group me-2"></i>Aktivitas Kelas {{ $kelas }}</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="row g-2 mb-3">
    <div class="col-4 col-md-2">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="h4 mb-0">{{ $rekap['hadir'] }}</div>
            <small class="text-muted">Hadir</small>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="h4 mb-0 text-warning">{{ $rekap['sakit'] }}</div>
            <small class="text-muted">Sakit</small>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="h4 mb-0 text-success">{{ $rekap['ijin'] }}</div>
            <small class="text-muted">Ijin</small>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="h4 mb-0 text-danger">{{ $rekap['alfa'] }}</div>
            <small class="text-muted">Alfa</small>
        </div>
    </div>
    <div class="col-4 col-md-2">
        <div class="bg-white rounded shadow p-3 text-center">
            <div class="h4 mb-0 text-info">{{ $rekap['dispensasi'] }}</div>
            <small class="text-muted">Dispensasi</small>
        </div>
    </div>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i>
            @if ($kelas === '')
                Akun ini belum terhubung ke kelas manapun (kolom <code>walikelas</code> di tabel <code>member</code> masih kosong).
            @else
                Belum ada siswa terdaftar di kelas {{ $kelas }}.
            @endif
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Induk</th>
                        <th>Nama</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $i => $s)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $s->id_member }}</td>
                            <td><a href="{{ route('siswa.profil', $s) }}">{{ $s->nama_lengkap }}</a></td>
                            <td>
                                @if ($s->absenHariIni)
                                    <span class="badge-status badge-{{ $s->absenHariIni->keterangan }}">
                                        {{ $s->absenHariIni->labelKeterangan() }}
                                    </span>
                                @else
                                    <span class="badge-status" style="background:#eaf3de; color:#3b6d11;">Hadir</span>
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
