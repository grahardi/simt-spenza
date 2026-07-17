@extends('layouts.app')

@section('title', 'Arsip Surat')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-envelope-open-text me-2"></i>Arsip Surat / Berkas Absensi</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($arsip->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada berkas terupload pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="row g-3">
            @foreach ($arsip as $a)
                <div class="col-6 col-md-3">
                    <a href="{{ Storage::url($a->gambar) }}" target="_blank" class="text-decoration-none text-dark">
                        <div class="border rounded p-2">
                            <img src="{{ Storage::url($a->gambar) }}" class="img-fluid rounded mb-2" style="aspect-ratio:1;object-fit:cover;width:100%;">
                            <div class="fw-semibold small">{{ $a->siswa->nama_lengkap ?? '-' }}</div>
                            <div class="text-muted small">
                                <span class="badge-status badge-{{ $a->keterangan }}">{{ $a->labelKeterangan() }}</span>
                                &middot; {{ $a->siswa->kelas ?? '-' }}
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        {{ $arsip->onEachSide(1)->links() }}
    @endif
</div>
@endsection
