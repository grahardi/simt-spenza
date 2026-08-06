@extends('layouts.app')

@section('title', 'Riwayat Absen Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-history fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Riwayat Absen Guru</h1>
    </div>
    <a href="{{ route('absen-guru.index') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Cari nama guru..." value="{{ $cari }}">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($riwayat->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada riwayat absen guru.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal</th><th>Guru</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($riwayat as $i => $r)
                    <tr role="button" data-bs-toggle="collapse" data-bs-target="#riwayatDetail{{ $i }}" style="cursor:pointer;">
                        <td class="text-nowrap">{{ $r->tanggal->translatedFormat('d M Y') }}</td>
                        <td>{{ $r->guru->nama ?? '-' }}</td>
                        <td><span class="badge-status badge-{{ $r->status }}">{{ $r->labelStatus() }}</span></td>
                        <td class="text-muted small"><i class="fas fa-chevron-down me-1"></i> Detail</td>
                    </tr>
                    <tr class="collapse" id="riwayatDetail{{ $i }}">
                        <td colspan="4" class="bg-light">
                            @php
                                $tugasHariItu = $tugasSemua[$r->id_guru.'|'.$r->tanggal->toDateString()] ?? collect();
                            @endphp
                            <p class="mb-2"><strong>Keterangan:</strong> {{ $r->keterangan ?: '-' }}</p>

                            @if ($r->foto)
                                <p class="mb-2">
                                    <strong>Surat:</strong>
                                    <a href="{{ Storage::url($r->foto) }}" target="_blank"><i class="fas fa-image me-1"></i>Lihat foto</a>
                                </p>
                            @endif

                            <strong class="d-block mb-1">Tugas yang diupload:</strong>
                            @if ($tugasHariItu->isEmpty())
                                <p class="text-muted small mb-0">Tidak ada tugas yang diupload untuk tanggal ini.</p>
                            @else
                                <ul class="mb-0 small">
                                    @foreach ($tugasHariItu as $t)
                                        <li>
                                            <strong>Kelas {{ $t->kelas }}</strong>: {{ $t->tugas }}
                                            @if ($t->gambar)
                                                - <a href="{{ Storage::url($t->gambar) }}" target="_blank">lihat lampiran</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

<div class="mt-3">
    {{ $riwayat->onEachSide(1)->links() }}
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
