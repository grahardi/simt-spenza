@extends('layouts.app')

@section('title', 'Galeri Kebersihan')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-images me-2"></i>Galeri Kebersihan (Sebelum &amp; Sesudah)</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($laporan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada laporan yang selesai ditindak.
        </div>
    @else
        <div class="row g-3">
            @foreach ($laporan as $l)
                <div class="col-6 col-md-3">
                    <div class="border rounded p-2">
                        <div class="fw-semibold small mb-1">Kelas {{ $l->kelas }}</div>
                        <div class="text-muted small mb-2">{{ $l->tanggal->translatedFormat('d M Y') }} &middot; {{ $l->guru->nama ?? '-' }}</div>
                        <div class="row g-1">
                            <div class="col-6">
                                <a href="{{ Storage::url($l->gambar) }}" target="_blank">
                                    <img src="{{ Storage::url($l->gambar) }}" class="img-fluid rounded" style="aspect-ratio:1;object-fit:cover;">
                                </a>
                                <div class="text-center small text-muted">Sebelum</div>
                            </div>
                            <div class="col-6">
                                <a href="{{ Storage::url($l->gambaraksi) }}" target="_blank">
                                    <img src="{{ Storage::url($l->gambaraksi) }}" class="img-fluid rounded" style="aspect-ratio:1;object-fit:cover;">
                                </a>
                                <div class="text-center small text-muted">Sesudah</div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{ $laporan->onEachSide(1)->links() }}
    @endif
</div>
@endsection
