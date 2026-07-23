@extends('layouts.app')

@php $dariPiket = $dariPiket ?? false; @endphp
@section('title', $dariPiket ? 'Ajuan Piket Guru' : 'Ajukan Absen Diri')

@php $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple']; @endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0">
        <i class="fas fa-user-clock me-2"></i>{{ $dariPiket ? 'Ajuan Piket Guru - '.$guru->nama : 'Ajukan Absen Diri' }}
    </h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0">Pilih Tanggal</label>
        <input type="date" name="tanggal" class="form-control" style="max-width:200px" value="{{ $tanggal->toDateString() }}" onchange="this.form.submit()">
    </form>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <h6 class="mb-2">Status untuk {{ $tanggal->translatedFormat('l, d F Y') }}</h6>
    @if ($absenTanggalItu)
        <span class="badge-status badge-{{ $absenTanggalItu->status }}">
            Sudah mengajukan {{ strtolower($absenTanggalItu->labelStatus()) }}
        </span>
        @if ($absenTanggalItu->keterangan)
            <span class="text-muted small ms-2">{{ $absenTanggalItu->keterangan }}</span>
        @endif
    @else
        <p class="text-muted small mb-2">Belum ada ajuan untuk tanggal ini.</p>
        <div class="d-flex flex-wrap gap-2">
            <button type="button" class="btn-absen btn-absen-sakit" data-bs-toggle="modal" data-bs-target="#modalAjuan-s">
                <i class="fas fa-thermometer me-1"></i> Sakit
            </button>
            <button type="button" class="btn-absen btn-absen-ijin" data-bs-toggle="modal" data-bs-target="#modalAjuan-i">
                <i class="fas fa-envelope me-1"></i> Ijin
            </button>
            <button type="button" class="btn-absen btn-absen-dispensasi" data-bs-toggle="modal" data-bs-target="#modalAjuan-d">
                <i class="fas fa-bus me-1"></i> Dispensasi
            </button>
        </div>
    @endif
</div>

@foreach (['s' => 'Sakit', 'i' => 'Ijin', 'd' => 'Dispensasi'] as $kode => $label)
    <div class="modal fade" id="modalAjuan-{{ $kode }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('ajuan-absen-guru.simpan') }}" class="modal-content">
                @csrf
                @if ($dariPiket)
                    <input type="hidden" name="id_guru" value="{{ $guru->id_guru }}">
                @endif
                <input type="hidden" name="tanggal" value="{{ $tanggal->toDateString() }}">
                <input type="hidden" name="status" value="{{ $kode }}">
                <div class="modal-header">
                    <h5 class="modal-title">Ajukan {{ $label }} - {{ $tanggal->translatedFormat('d F Y') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" class="form-control" placeholder="contoh: demam, ada urusan keluarga">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Ajuan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<div class="p-4 bg-white rounded shadow">
    <h6 class="mb-3">Jadwal Mengajar {{ $namaHari }}</h6>
    @if ($jadwalHariItu->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada jadwal mengajar pada hari {{ $namaHari }}.
        </div>
    @else
        <div class="d-flex flex-column gap-2">
            @php $warnaIndex = -1; $kunciSebelumnya = null; @endphp
            @foreach ($jadwalHariItu->sortBy('jamhari') as $j)
                @php
                    $kunciSekarang = $j->kelas.'|'.$j->mapel;
                    $blokBaru = $kunciSekarang !== $kunciSebelumnya;
                    if ($blokBaru) {
                        $warnaIndex++;
                    }
                    $kunciSebelumnya = $kunciSekarang;
                    $warna = $palet[$warnaIndex % count($palet)];

                    // Tombol Upload/Lihat Tugas cuma di baris pertama tiap blok
                    // kelas+mapel, dan cuma kalau sudah ada ajuan (bukan Alfa).
                    $tampilkanTombolTugas = $blokBaru && $absenTanggalItu;
                    $tugasSudahAda = $tampilkanTombolTugas ? ($tugasTanggalItu[$j->kelas] ?? null) : null;
                @endphp
                <div class="jadwal-baris bg-{{ $warna }}">
                    <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                    <span class="jadwal-waktu-kecil">{{ $j->waktu ?? '-' }}</span>
                    <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                    <span class="jadwal-mapel-kecil">{{ $j->mapelLengkap() }}</span>
                    @if ($tampilkanTombolTugas)
                        <a href="{{ route('tugas.upload', [$guru, $j->kelas]) }}?tanggal={{ $tanggal->toDateString() }}&dari_ajuan_sendiri=1{{ $dariPiket ? '&dari_piket=1' : '' }}" class="btn btn-sm btn-outline-dark" style="border-color:currentColor;color:inherit;">
                            @if ($tugasSudahAda)
                                <i class="fas fa-eye me-1"></i> Lihat Tugas
                            @else
                                <i class="fas fa-clipboard-list me-1"></i> Upload Tugas
                            @endif
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
        @if (! $absenTanggalItu)
            <p class="text-muted small mt-3 mb-0">
                <i class="fas fa-info-circle me-1"></i> Tombol Upload Tugas akan muncul di sini setelah ajuan absen dikirim.
            </p>
        @endif
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
