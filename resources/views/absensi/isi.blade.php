@extends('layouts.app')

@section('title', 'Isi Absensi')

@section('content')
@include('partials.menu-absensi')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-search fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Pengisian Absensi</h1>
    </div>
    <a href="{{ route('absensi.telat-isi') }}" class="btn btn-light btn-sm mt-2 mt-md-0 me-2">
        <i class="fas fa-clock me-1"></i> Isi Keterlambatan
    </a>
    <a href="{{ route('absensi.telat.list') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-list me-1"></i> Data Terlambat
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

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
    <div class="bg-white rounded shadow overflow-hidden">
        @if ($siswa->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Siswa tidak ditemukan.
            </div>
        @else
            <div class="table-responsive">
            <table class="table mb-0 align-middle">
                <thead>
                    <tr><th>No. Induk</th><th>Nama</th><th>Kelas</th><th>Status</th><th style="width:320px">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach ($siswa as $s)
                        <tr style="background:{{ $s->jenis_kelamin === 'P' ? '#fde9ec' : '#e6f7ea' }};">
                            <td>{{ $s->id_member }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td>
                                @if ($s->absenHariIni)
                                    <span class="badge-status badge-{{ $s->absenHariIni->keterangan }}">
                                        {{ $s->absenHariIni->labelKeterangan() }}
                                    </span>
                                @else
                                    <span class="text-muted small">Belum diabsen</span>
                                @endif
                            </td>
                            <td>
                                @if (! $s->absenHariIni)
                                    <div class="d-flex flex-wrap gap-1">
                                        <button type="button" class="btn-absen btn-absen-sakit" data-bs-toggle="modal" data-bs-target="#modalSakit{{ $s->id_member }}">
                                            <i class="fas fa-thermometer me-1"></i> Sakit
                                        </button>
                                        <button type="button" class="btn-absen btn-absen-ijin" data-bs-toggle="modal" data-bs-target="#modalIjin{{ $s->id_member }}">
                                            <i class="fas fa-envelope me-1"></i> Ijin
                                        </button>
                                        <button type="button" class="btn-absen btn-absen-dispensasi" data-bs-toggle="modal" data-bs-target="#modalDispensasi{{ $s->id_member }}">
                                            <i class="fas fa-bus me-1"></i> Dispensasi
                                        </button>
                                        <form method="POST" action="{{ route('absensi.tandai', $s) }}" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="keterangan" value="a">
                                            <button type="submit" class="btn-absen btn-absen-alfa">
                                                <i class="fas fa-times me-1"></i> Alfa
                                            </button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        @endif
    </div>

    @foreach ($siswa as $s)
        @if (! $s->absenHariIni)
            <div class="modal fade" id="modalSakit{{ $s->id_member }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('absensi.tandai', $s) }}" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        <input type="hidden" name="keterangan" value="s">
                        <div class="modal-header">
                            <h5 class="modal-title">Absen Sakit - {{ $s->nama_lengkap }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Foto Bukti (opsional)</label>
                                <input type="file" name="foto" accept="image/*" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan (opsional)</label>
                                <input type="text" name="catatan" class="form-control" placeholder="contoh: demam, izin dari orang tua">
                            </div>
                            <p class="text-muted small mb-0">Foto & keterangan boleh dikosongkan - klik Absen untuk simpan langsung.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-warning">Absen</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="modalIjin{{ $s->id_member }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('absensi.tandai', $s) }}" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        <input type="hidden" name="keterangan" value="i">
                        <div class="modal-header">
                            <h5 class="modal-title">Absen Ijin - {{ $s->nama_lengkap }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Foto Bukti (opsional)</label>
                                <input type="file" name="foto" accept="image/*" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan (opsional)</label>
                                <input type="text" name="catatan" class="form-control" placeholder="contoh: acara keluarga">
                            </div>
                            <p class="text-muted small mb-0">Foto & keterangan boleh dikosongkan - klik Absen untuk simpan langsung.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Absen</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="modal fade" id="modalDispensasi{{ $s->id_member }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('absensi.tandai', $s) }}" enctype="multipart/form-data" class="modal-content">
                        @csrf
                        <input type="hidden" name="keterangan" value="d">
                        <div class="modal-header">
                            <h5 class="modal-title">Absen Dispensasi - {{ $s->nama_lengkap }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Foto Bukti (opsional)</label>
                                <input type="file" name="foto" accept="image/*" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan (opsional)</label>
                                <input type="text" name="catatan" class="form-control" placeholder="contoh: lomba, kegiatan dinas sekolah">
                            </div>
                            <p class="text-muted small mb-0">Foto & keterangan boleh dikosongkan - klik Absen untuk simpan langsung.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-info text-white">Absen</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
