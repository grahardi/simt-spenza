@extends('layouts.app')

@section('title', 'Ajuan WhatsApp')

@section('content')
@include('partials.menu-absensi')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fab fa-whatsapp me-2"></i>Ajuan WhatsApp</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('ajuan-whatsapp.index', ['status' => 'menunggu']) }}" class="tab-tahun {{ $status === 'menunggu' ? 'active' : '' }}" style="text-decoration:none; display:inline-block;">Menunggu</a>
    <a href="{{ route('ajuan-whatsapp.index', ['status' => 'disetujui']) }}" class="tab-tahun {{ $status === 'disetujui' ? 'active' : '' }}" style="text-decoration:none; display:inline-block;">Disetujui</a>
    <a href="{{ route('ajuan-whatsapp.index', ['status' => 'ditolak']) }}" class="tab-tahun {{ $status === 'ditolak' ? 'active' : '' }}" style="text-decoration:none; display:inline-block;">Ditolak</a>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($ajuan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada ajuan WhatsApp berstatus "{{ $status }}".
        </div>
    @else
        @foreach ($ajuan as $a)
            <div class="ajuan-wa-card {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="ajuan-wa-info">
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <strong class="text-uppercase">{{ $a->siswa->nama_lengkap ?? '-' }}</strong>
                        <span class="badge-status badge-{{ $a->jenis }}">{{ $a->labelJenis() }}</span>
                    </div>
                    <div class="text-muted small mt-1">
                        Kelas {{ $a->siswa->kelas ?? '-' }} &middot; {{ $a->created_at->translatedFormat('d F Y H:i') }}
                    </div>
                    <div class="text-muted small">
                        @php
                            $label = $a->labelPengaju();
                            $ikon = match ($label) {
                                'Ayah' => 'fas fa-male',
                                'Ibu' => 'fas fa-female',
                                default => 'fas fa-user-friends',
                            };
                        @endphp
                        <i class="{{ $ikon }} me-1"></i> Diajukan oleh <strong>{{ $label ?? 'Wali/Lainnya' }}</strong>
                        <span class="fst-italic">- notifikasi ACC/Tolak otomatis kembali ke nomor yang sama</span>
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFotoSurat{{ $a->id }}">
                            <i class="fas fa-image me-1"></i> Lihat Surat
                        </button>
                        @if ($a->foto_selfie)
                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#modalFotoSelfie{{ $a->id }}">
                                <i class="fas fa-user me-1"></i> Lihat Selfie Wali
                            </button>
                        @endif
                        <a href="https://wa.me/{{ $a->nomor_wa }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp me-1"></i> Kirim WA
                        </a>
                    </div>
                    @if ($a->status === 'ditolak' && $a->alasan_tolak)
                        <div class="text-muted small mt-1">
                            <i class="fas fa-comment-dots me-1"></i> Alasan ditolak: <em>{{ $a->alasan_tolak }}</em>
                        </div>
                    @endif
                </div>

                @if ($status === 'menunggu' && auth('member')->user()->hasRole('piket'))
                    <div class="ajuan-wa-aksi mt-2 mt-md-0">
                        <form method="POST" action="{{ route('ajuan-whatsapp.acc', $a) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i> ACC</button>
                        </form>
                        <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak{{ $a->id }}">
                            <i class="fas fa-times me-1"></i> Tolak
                        </button>
                    </div>
                @endif
            </div>
        @endforeach

        @foreach ($ajuan as $a)
            <div class="modal fade" id="modalFotoSurat{{ $a->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Foto Surat - {{ $a->siswa->nama_lengkap ?? '-' }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center">
                            <img src="{{ Storage::url($a->foto_surat) }}" alt="Foto surat" class="img-fluid rounded">
                        </div>
                    </div>
                </div>
            </div>
            @if ($a->foto_selfie)
                <div class="modal fade" id="modalFotoSelfie{{ $a->id }}" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Selfie Wali - {{ $a->siswa->nama_lengkap ?? '-' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body text-center">
                                <img src="{{ Storage::url($a->foto_selfie) }}" alt="Foto selfie wali" class="img-fluid rounded">
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if ($status === 'menunggu')
                <div class="modal fade" id="modalTolak{{ $a->id }}" tabindex="-1">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('ajuan-whatsapp.tolak', $a) }}" class="modal-content">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title">Tolak Ajuan - {{ $a->siswa->nama_lengkap ?? '-' }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <label class="form-label">Alasan Penolakan (opsional, akan dikirim ke wali murid)</label>
                                <textarea name="alasan_tolak" class="form-control" rows="3" placeholder="contoh: surat kurang jelas, silakan datang langsung ke sekolah"></textarea>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-danger"><i class="fas fa-times me-1"></i> Tolak</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endif
        @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

{{ $ajuan->onEachSide(1)->links() }}
@endsection
