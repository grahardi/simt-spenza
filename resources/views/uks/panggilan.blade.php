@extends('layouts.app')

@section('title', 'Panggilan Wali Murid')

@section('content')
@include('partials.menu-uks')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-phone-alt fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Panggilan Wali Murid</h1>
    </div>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-6">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama atau nomor induk siswa..." value="{{ $cari }}" autofocus>
        </div>
        <div class="col-md-4">
            <select name="kelas" class="form-select">
                <option value="">Semua kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected($kelasFilter === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

@if ($siswa !== null)
    <div class="bg-white rounded shadow overflow-hidden mb-4">
        @if ($siswa->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Siswa tidak ditemukan.
            </div>
        @else
            <div class="table-responsive">
            <table class="table table-striped mb-0 align-middle">
                <thead>
                    <tr><th>No. Induk</th><th>Nama</th><th>Kelas</th><th>Nomor Wali</th><th></th></tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr>
                            <td>{{ $s->id_member }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td>
                                @forelse ($s->nomorWhatsapp as $nw)
                                    <span class="badge bg-secondary">{{ $nw->label ?? 'Wali' }}</span>
                                @empty
                                    <span class="text-muted small">Belum registrasi</span>
                                @endforelse
                            </td>
                            <td class="text-end">
                                @foreach ($s->nomorWhatsapp as $nw)
                                    <a href="https://wa.me/{{ $nw->nomor }}" target="_blank" class="btn btn-sm btn-success">
                                        <i class="fab fa-whatsapp me-1"></i> {{ $nw->label ?? 'WA' }}
                                    </a>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>
@endif

<h6 class="mb-2"><i class="fas fa-bed me-1"></i> Siswa yang Sedang di UKS Hari Ini</h6>
<div class="bg-white rounded shadow overflow-hidden">
    @if ($sedangDiUks->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa di UKS saat ini.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Waktu Masuk</th><th>Nama</th><th>Kelas</th><th>Keterangan</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($sedangDiUks as $d)
                    <tr>
                        <td>{{ $d->waktu_masuk->format('H:i') }}</td>
                        <td>{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->siswa->kelas ?? '-' }}</td>
                        <td>{{ $d->keterangan_sakit ?: '-' }}</td>
                        <td class="text-end">
                            @forelse ($d->siswa->nomorWhatsapp ?? [] as $nw)
                                <a href="https://wa.me/{{ $nw->nomor }}" target="_blank" class="btn btn-sm btn-success">
                                    <i class="fab fa-whatsapp me-1"></i> {{ $nw->label ?? 'WA' }}
                                </a>
                            @empty
                                <span class="text-muted small">Wali belum registrasi WA</span>
                            @endforelse
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
