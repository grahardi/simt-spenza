@extends('layouts.app')

@section('title', 'Rekap Terbanyak - Pelanggaran Keagamaan')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-mosque fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Rekap Terbanyak - Pelanggaran Keagamaan</h1>
    </div>
    <a href="{{ route('pelanggaran-keagamaan.rekap-harian') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-calendar-day me-1"></i> Rekap Harian
    </a>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow d-flex gap-2 flex-wrap">
    @foreach (\App\Models\PelanggaranKeagamaan::LABEL_STATUS as $kode => $label)
        <a href="{{ route('pelanggaran-keagamaan.rekap-terbanyak', ['status' => $kode]) }}"
           class="btn btn-sm {{ $status === $kode ? 'btn-primary' : 'btn-outline-primary' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada data untuk status "{{ \App\Models\PelanggaranKeagamaan::LABEL_STATUS[$status] }}".
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>No</th><th>Nama</th><th>Kelas</th><th class="text-center">Jumlah {{ \App\Models\PelanggaranKeagamaan::LABEL_STATUS[$status] }}</th></tr>
            </thead>
            <tbody>
                @foreach ($rekap as $i => $r)
                    <tr>
                        <td>{{ $rekap->firstItem() + $i }}</td>
                        <td>{{ $r->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $r->kelas }}</td>
                        <td class="text-center"><span class="badge bg-primary">{{ $r->jumlah }}</span></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

<div class="mt-3">
    {{ $rekap->onEachSide(1)->links() }}
</div>
@endsection
