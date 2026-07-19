@extends('layouts.app')

@section('title', 'Ajuan WhatsApp')

@section('content')
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
                        <i class="fab fa-whatsapp me-1"></i> Diajukan dari nomor <strong>{{ $a->nomor_wa }}</strong>
                        @if ($a->labelPengaju())
                            <span class="badge bg-secondary">{{ $a->labelPengaju() }}</span>
                        @endif
                        <span class="fst-italic">- notifikasi ACC/Tolak akan dikirim balik ke nomor ini juga</span>
                    </div>

                    <div class="mt-2 d-flex flex-wrap gap-2">
                        <a href="{{ Storage::url($a->foto_surat) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-image me-1"></i> Lihat Surat
                        </a>
                        @if ($a->foto_selfie)
                            <a href="{{ Storage::url($a->foto_selfie) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-user me-1"></i> Lihat Selfie Wali
                            </a>
                        @endif
                    </div>
                </div>

                @if ($status === 'menunggu')
                    <div class="ajuan-wa-aksi mt-2 mt-md-0">
                        <form method="POST" action="{{ route('ajuan-whatsapp.acc', $a) }}" class="d-inline">
                            @csrf
                            <button type="submit" class="btn btn-success btn-sm"><i class="fas fa-check me-1"></i> ACC</button>
                        </form>
                        <form method="POST" action="{{ route('ajuan-whatsapp.tolak', $a) }}" class="d-inline" onsubmit="return confirm('Yakin tolak ajuan ini?')">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times me-1"></i> Tolak</button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

{{ $ajuan->onEachSide(1)->links() }}
@endsection
