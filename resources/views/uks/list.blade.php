@extends('layouts.app')

@section('title', 'Siswa di UKS')

@section('content')
@include('partials.menu-uks')

<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-bed fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Siswa di UKS</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px" value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada data UKS pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Waktu Masuk</th><th>Nama</th><th>Kelas</th><th>Keterangan Sakit</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $d)
                    <tr>
                        <td>{{ $d->waktu_masuk->format('H:i') }}</td>
                        <td>{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->siswa->kelas ?? '-' }}</td>
                        <td>{{ $d->keterangan_sakit ?: '-' }}</td>
                        <td>
                            <span class="badge {{ $d->status === 'di_uks' ? 'bg-warning text-dark' : 'bg-success' }}">
                                {{ $d->labelStatus() }}
                            </span>
                            @if ($d->keterangan_penanganan)
                                <div class="small text-muted">{{ $d->keterangan_penanganan }}</div>
                            @endif
                        </td>
                        <td class="text-end">
                            @if ($d->status === 'di_uks')
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Penanganan
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        @foreach (['kembali_kelas' => 'Kembali ke Kelas', 'pulang_dijemput' => 'Pulang Dijemput', 'puskesmas' => 'Puskesmas', 'lainnya' => 'Lainnya'] as $kode => $label)
                                            <li>
                                                <form method="POST" action="{{ route('uks.penanganan', $d) }}" class="px-2 {{ $kode === 'lainnya' ? '' : 'pb-1' }}">
                                                    @csrf
                                                    <input type="hidden" name="status" value="{{ $kode }}">
                                                    @if ($kode === 'lainnya')
                                                        <input type="text" name="keterangan_penanganan" class="form-control form-control-sm mb-1" placeholder="Keterangan...">
                                                    @endif
                                                    <button type="submit" class="dropdown-item ps-0">{{ $label }}</button>
                                                </form>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
