@extends('layouts.app')

@section('title', 'Ajukan Absensi - ' . $kelas)

@section('content')
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
            @php $ajuanIni = $s->ajuanHariIni; @endphp
            <div class="siswa-row {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="siswa-info">
                    <h6 class="mb-0 text-uppercase">
                        <span class="text-primary">{{ $s->id_member }}</span> - {{ $s->nama_lengkap }}
                    </h6>
                    <small class="text-muted">No. Induk {{ $s->id_member }} &middot; Kelas {{ $s->kelas }}</small>

                    @if ($ajuanIni)
                        <div class="mt-1">
                            <span class="badge-status badge-{{ $ajuanIni->keterangan }}">
                                <i class="fas fa-hourglass-half me-1"></i>
                                Ajuan {{ strtolower($ajuanIni->labelKeterangan()) }} terkirim, menunggu ACC piket
                            </span>
                        </div>
                    @endif
                </div>

                @if (! $ajuanIni)
                    <div class="siswa-aksi">
                        <button type="button" class="btn-absen btn-absen-sakit" data-bs-toggle="modal" data-bs-target="#modalSakit{{ $s->id_member }}">
                            <i class="fas fa-thermometer me-1"></i> Sakit
                        </button>
                        <button type="button" class="btn-absen btn-absen-ijin" data-bs-toggle="modal" data-bs-target="#modalIjin{{ $s->id_member }}">
                            <i class="fas fa-envelope me-1"></i> Ijin
                        </button>
                        <button type="button" class="btn-absen btn-absen-dispensasi" data-bs-toggle="modal" data-bs-target="#modalDispensasi{{ $s->id_member }}">
                            <i class="fas fa-bus me-1"></i> Dispensasi
                        </button>
                    </div>
                @endif
            </div>

            @foreach (['s' => ['Sakit', 'warning', 'demam, izin dari orang tua'], 'i' => ['Ijin', 'success', 'acara keluarga'], 'd' => ['Dispensasi', 'info', 'lomba, kegiatan dinas sekolah']] as $kode => $meta)
                <div class="modal fade" id="modal{{ $kode === 's' ? 'Sakit' : ($kode === 'i' ? 'Ijin' : 'Dispensasi') }}{{ $s->id_member }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('ajuan-absensi.simpan', $s) }}" enctype="multipart/form-data" class="modal-content">
                            @csrf
                            <input type="hidden" name="keterangan" value="{{ $kode }}">
                            <div class="modal-header">
                                <h5 class="modal-title">Ajukan {{ $meta[0] }} - {{ $s->nama_lengkap }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Foto Surat/Bukti (opsional)</label>
                                    <input type="file" name="foto" accept="image/*" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Keterangan (opsional)</label>
                                    <input type="text" name="catatan" class="form-control" placeholder="contoh: {{ $meta[2] }}">
                                </div>
                                <p class="text-muted small mb-0">Ajuan ini masih menunggu ACC piket, belum otomatis jadi absensi resmi.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-{{ $meta[1] }} {{ $meta[1] === 'info' ? 'text-white' : '' }}">Ajukan</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
