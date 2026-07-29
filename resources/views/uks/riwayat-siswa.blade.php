@extends('layouts.app')

@section('title', 'Riwayat Siswa - UKS')

@section('content')
@include('partials.menu-uks')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-history me-2"></i>Riwayat Siswa - UKS</h1>
</div>

<div class="p-4 bg-white rounded shadow">
    <p class="text-muted small">Diurutkan dari yang paling sering ke UKS. Klik nama untuk lihat riwayat kunjungannya.</p>

    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada data kunjungan UKS.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Kelas</th><th class="text-center">Jumlah Kunjungan</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($rekap as $i => $r)
                    @php $s = $siswaMap[$r->id_siswa] ?? null; @endphp
                    <tr role="button" data-bs-toggle="collapse" data-bs-target="#riwayat{{ $r->id_siswa }}" style="cursor:pointer;">
                        <td>{{ $rekap->firstItem() + $i }}</td>
                        <td>{{ $s->nama_lengkap ?? '-' }} <i class="fas fa-chevron-down text-muted small ms-1"></i></td>
                        <td>{{ $s->kelas ?? '-' }}</td>
                        <td class="text-center"><span class="badge bg-primary">{{ $r->jumlah_kunjungan }}</span></td>
                        <td class="text-muted small">Lihat riwayat</td>
                    </tr>
                    <tr class="collapse" id="riwayat{{ $r->id_siswa }}">
                        <td colspan="5" class="bg-light p-0">
                            <table class="table table-sm mb-0">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Tanggal</th><th>Keluhan</th><th>Status</th>
                                        <th>Masuk</th><th>Selesai</th><th>Penanganan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($riwayatPerSiswa[$r->id_siswa] ?? [] as $k)
                                        <tr>
                                            <td class="ps-3">{{ $k->tanggal?->translatedFormat('d M Y') ?? '-' }}</td>
                                            <td>{{ $k->keterangan_sakit }}</td>
                                            <td><span class="badge-status">{{ $k->labelStatus() }}</span></td>
                                            <td>{{ $k->waktu_masuk?->format('H:i') ?? '-' }}</td>
                                            <td>{{ $k->waktu_selesai?->format('H:i') ?? '-' }}</td>
                                            <td>{{ $k->keterangan_penanganan ?? '-' }}</td>
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

        <div class="mt-3">
            {{ $rekap->onEachSide(1)->links() }}
        </div>
    @endif
</div>
@endsection
