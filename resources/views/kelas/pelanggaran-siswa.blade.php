@extends('layouts.app')

@section('title', 'Data Pelanggaran Siswa')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-exclamation-circle me-2"></i>Data Pelanggaran Siswa - Kelas {{ $kelas }}</h1>
</div>

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
