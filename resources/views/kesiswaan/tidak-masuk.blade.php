@extends('layouts.app')

@section('title', 'Siswa Tidak Masuk 3+ Hari')

@section('content')
@include('partials.menu-kesiswaan')

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
        Dihitung dari gabungan <strong>Sakit + Alfa</strong>. Klik baris untuk lihat rincian tanggal.
    </p>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa yang Sakit/Alfa 3 hari atau lebih di minggu ini.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Kelas</th><th>Jumlah (Sakit+Alfa)</th><th></th>
                    @if (auth('member')->user()->hasRole('tatib'))
                        <th>Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @foreach ($daftar as $i => $d)
                    <tr>
                        <td role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer;">{{ $i + 1 }}</td>
                        <td role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer;">{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer;">{{ $d->siswa->kelas ?? '-' }}</td>
                        <td role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer;"><span class="badge bg-danger">{{ $d->jumlah }} hari</span></td>
                        <td class="text-muted small" role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer;"><i class="fas fa-chevron-down"></i> Lihat rincian</td>
                        @if (auth('member')->user()->hasRole('tatib'))
                            <td class="text-nowrap">
                                @php $nomorWali = $d->siswa->nomorWhatsapp ?? collect(); @endphp
                                @forelse ($nomorWali as $nw)
                                    <a href="https://wa.me/{{ $nw->nomor }}" target="_blank" class="btn btn-sm btn-success mb-1">
                                        <i class="fab fa-whatsapp me-1"></i> {{ $nw->label ?? 'Wali' }}
                                    </a>
                                @empty
                                    <span class="text-muted small d-block">Wali belum registrasi WA</span>
                                @endforelse
                                <a href="{{ route('tatib.lapor', $d->siswa) }}?keterangan={{ urlencode('Tidak masuk (Sakit/Alfa) '.$d->jumlah.' hari dalam seminggu ini.') }}" class="btn btn-sm btn-outline-danger mb-1">
                                    <i class="fas fa-gavel me-1"></i> Lapor Pelanggaran
                                </a>
                            </td>
                        @endif
                    </tr>
                    <tr class="collapse" id="detail{{ $i }}">
                        <td colspan="6" class="bg-light">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr><th>Tanggal</th><th>Hari</th><th>Status</th><th>Keterangan</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($d->detail as $rec)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($rec->tgl_absen)->translatedFormat('d F Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($rec->tgl_absen)->translatedFormat('l') }}</td>
                                            <td><span class="badge-status badge-{{ $rec->keterangan }}">{{ $rec->labelKeterangan() }}</span></td>
                                            <td>{{ $rec->tambahan ?: '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
