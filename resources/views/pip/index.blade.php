@extends('layouts.app')

@section('title', 'Data PIP')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-hand-holding-usd me-2"></i>
        <h1 class="h5 pt-2 mb-0">Data PIP</h1>
    </div>
    @if (auth('member')->user()->hasRole('walikelas'))
        <a href="{{ route('pip.list-teks') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
            <i class="fas fa-clipboard me-1"></i> List untuk WhatsApp
        </a>
    @endif
</div>

@if ($daftarKelas->isNotEmpty())
    <div class="px-4 py-3 mb-3 bg-white rounded shadow">
        <form method="GET" class="d-flex gap-2 align-items-center flex-wrap">
            <label class="form-label mb-0">Kelas</label>
            <select name="kelas" class="form-select" style="max-width:200px" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                @endforeach
            </select>
            <label class="form-label mb-0 ms-2">Status Cair</label>
            <select name="status" class="form-select" style="max-width:200px" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="sudah" @selected(request('status') === 'sudah')>Sudah Cair</option>
                <option value="belum" @selected(request('status') === 'belum')>Belum Cair</option>
            </select>
        </form>
    </div>
@else
    <div class="px-4 py-3 mb-3 bg-white rounded shadow">
        <form method="GET" class="d-flex gap-2 align-items-center">
            <label class="form-label mb-0">Status Cair</label>
            <select name="status" class="form-select" style="max-width:200px" onchange="this.form.submit()">
                <option value="">Semua</option>
                <option value="sudah" @selected(request('status') === 'sudah')>Sudah Cair</option>
                <option value="belum" @selected(request('status') === 'belum')>Belum Cair</option>
            </select>
        </form>
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada data PIP.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Nama</th><th>Kelas</th><th>Nominal</th><th>No. Rekening</th><th>Status Cair</th><th>Tahap</th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $p)
                    <tr role="button" onclick="window.location='{{ route('pip.show', $p) }}'" style="cursor:pointer;">
                        <td>{{ $p->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $p->siswa->kelas ?? '-' }}</td>
                        <td>Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td>
                        <td>{{ $p->no_rekening }}</td>
                        <td>
                            <span class="badge {{ str_contains(strtolower($p->status_cair ?? ''), 'sudah') ? 'bg-success' : 'bg-secondary' }}">
                                {{ $p->status_cair ?? '-' }}
                            </span>
                        </td>
                        <td class="small">{{ $p->tahap_keterangan }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
