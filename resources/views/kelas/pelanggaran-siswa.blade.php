@extends('layouts.app')

@section('title', 'Data Pelanggaran Siswa')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i>Data Pelanggaran Siswa - Kelas {{ $kelas }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@if ($rujukanMenunggu->isNotEmpty())
    <div class="p-4 bg-white rounded shadow mb-3">
        <h6 class="mb-3"><i class="fas fa-bell text-warning me-1"></i> Rujukan Masuk dari Tatib ({{ $rujukanMenunggu->count() }})</h6>
        @foreach ($rujukanMenunggu as $r)
            <div class="border rounded p-3 mb-2">
                <div class="d-flex justify-content-between flex-wrap">
                    <div>
                        <strong>{{ $r->siswa->nama_lengkap ?? '-' }}</strong>
                        <span class="text-muted small ms-2">{{ $r->created_at->translatedFormat('d M Y, H:i') }}</span>
                        <div class="text-muted small mt-1">{{ $r->alasan }}</div>
                    </div>
                </div>
                <div class="d-flex flex-wrap gap-2 mt-2">
                    <form method="POST" action="{{ route('aktivitas-kelas.rujukan.tindak', $r) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="tindak_lanjut" value="konfirmasi">
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-check me-1"></i> Konfirmasi Saja
                        </button>
                    </form>

                    @forelse ($r->siswa->nomorWhatsapp ?? [] as $nw)
                        <a href="https://wa.me/{{ $nw->nomor }}" target="_blank" class="btn btn-sm btn-success">
                            <i class="fab fa-whatsapp me-1"></i> Hubungi {{ $nw->label ?? 'Ortu' }}
                        </a>
                    @empty
                        <span class="text-muted small align-self-center">Wali belum registrasi WA</span>
                    @endforelse

                    <form method="POST" action="{{ route('aktivitas-kelas.rujukan.tindak', $r) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="tindak_lanjut" value="ajukan_bk">
                        <button type="submit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-hands-helping me-1"></i> Ajukan BK
                        </button>
                    </form>
                    <form method="POST" action="{{ route('aktivitas-kelas.rujukan.tindak', $r) }}" class="d-inline">
                        @csrf
                        <input type="hidden" name="tindak_lanjut" value="ajukan_tatib">
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-gavel me-1"></i> Ajukan Tatib
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada catatan pelanggaran untuk siswa di kelas ini.
        </div>
    @else
        <p class="text-muted small">Klik nama siswa untuk lihat rincian pelanggarannya. Diurutkan dari poin terbanyak.</p>
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Nama</th><th class="text-center" style="width:120px">Jumlah Kasus</th><th class="text-center" style="width:120px">Total Poin</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($rekap as $i => $r)
                    <tr role="button" data-bs-toggle="collapse" data-bs-target="#detail{{ $i }}" style="cursor:pointer; {{ $r->totalPoin >= 50 ? 'background:#fcebeb;' : ($r->totalPoin >= 25 ? 'background:#fff3cd;' : '') }}">
                        <td>{{ $r->siswa->nama_lengkap ?? '-' }}</td>
                        <td class="text-center">{{ $r->jumlahKasus }}</td>
                        <td class="text-center fw-bold">{{ $r->totalPoin }}</td>
                        <td class="text-muted small"><i class="fas fa-chevron-down"></i> Rincian</td>
                    </tr>
                    <tr class="collapse" id="detail{{ $i }}">
                        <td colspan="4" class="bg-light">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr><th>Tanggal</th><th>Kategori</th><th>Keterangan</th><th class="text-center">Poin</th></tr>
                                </thead>
                                <tbody>
                                    @foreach ($r->daftar as $p)
                                        <tr>
                                            <td>{{ $p->tgl_pelanggaran?->translatedFormat('d F Y') ?? '-' }}</td>
                                            <td>{{ $p->kategori }}</td>
                                            <td>{{ $p->keterangan }}</td>
                                            <td class="text-center">{{ $p->poin }}</td>
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
