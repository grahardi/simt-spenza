@extends('layouts.app')

@section('title', 'Ajukan Absensi - ' . $kelas)

@section('content')
@include('partials.menu-absensi')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-inbox fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Ajukan Absensi - Kelas {{ $kelas }}</h1>
    </div>
    <a href="{{ route('ajuan-absensi.pilih-kelas') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Ganti Kelas
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info small">
    <i class="fas fa-info-circle me-1"></i>
    Ajuan di sini <strong>belum langsung tercatat sebagai absensi resmi</strong> - menunggu di-ACC oleh petugas piket dulu.
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada siswa terdaftar di kelas {{ $kelas }}.
        </div>
    @else
        @foreach ($siswa as $s)
            @php $ajuanIni = $s->ajuanHariIni; $kemarin = $s->absenSebelumnya; $resmiIni = $s->absenResmiHariIni; @endphp
            <div class="siswa-row-ringkas {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="siswa-nama">
                    <span class="text-primary">{{ $s->id_member }}</span> - {{ $s->nama_lengkap }}
                    <div class="text-muted" style="font-size:11px">
                        Kemarin: {{ $kemarin ? $kemarin->labelKeterangan() : 'Hadir' }}
                    </div>
                </div>

                @if ($resmiIni)
                    {{-- Sudah tercatat resmi di absen_siswa (via Isi Absensi piket atau sudah di-ACC) -
                         tidak perlu dan tidak bisa diajukan lagi. --}}
                    <span class="badge-status badge-{{ $resmiIni->keterangan }}">
                        <i class="fas fa-check-circle me-1"></i> Siswa Terabsen {{ $resmiIni->labelKeterangan() }}
                    </span>
                @elseif ($ajuanIni)
                    <span class="badge-status badge-{{ $ajuanIni->keterangan }}">
                        <i class="fas fa-hourglass-half me-1"></i> Sudah diajukan {{ strtolower($ajuanIni->labelKeterangan()) }}
                    </span>
                @else
                    <div class="siswa-aksi">
                        <form method="POST" action="{{ route('ajuan-absensi.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="s">
                            <button type="submit" class="btn-absen btn-absen-sakit">
                                <i class="fas fa-thermometer me-1"></i> Sakit
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ajuan-absensi.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="i">
                            <button type="submit" class="btn-absen btn-absen-ijin">
                                <i class="fas fa-envelope me-1"></i> Ijin
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ajuan-absensi.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="d">
                            <button type="submit" class="btn-absen btn-absen-dispensasi">
                                <i class="fas fa-bus me-1"></i> Dispensasi
                            </button>
                        </form>
                        <form method="POST" action="{{ route('ajuan-absensi.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="keterangan" value="a">
                            <button type="submit" class="btn-absen btn-absen-alfa">
                                <i class="fas fa-times me-1"></i> Alfa
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
