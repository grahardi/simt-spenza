@extends('layouts.app')

@section('title', 'List Ajuan')

@section('content')
@include('partials.menu-absensi')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-list fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">List Ajuan</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex align-items-center gap-2 flex-wrap">
        <label class="form-label mb-0"><i class="fas fa-calendar-alt me-1"></i> Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">

        <div class="btn-group ms-auto">
            <a href="{{ route('ajuan-absensi.list', ['tgl' => \Carbon\Carbon::yesterday()->format('Y-m-d')]) }}" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-chevron-left"></i> Kemarin
            </a>
            <a href="{{ route('ajuan-absensi.list') }}" class="btn btn-outline-primary btn-sm">Hari Ini</a>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h5 mb-3">
        <i class="fas fa-clone me-2"></i>
        Ajuan {{ $tanggal->translatedFormat('d F Y') }}
        <span class="badge bg-secondary">{{ $ajuan->total() }}</span>
    </h3>

    @if ($ajuan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada ajuan pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No. Induk</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Ajuan</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ajuan as $i => $a)
                        <tr>
                            <td>{{ $ajuan->firstItem() + $i }}</td>
                            <td>{{ $a->id_siswa }}</td>
                            <td>{{ $a->siswa->nama_lengkap ?? '-' }}</td>
                            <td>{{ $a->siswa->kelas ?? '-' }}</td>
                            <td><span class="badge-status badge-{{ $a->keterangan }}">{{ $a->labelKeterangan() }}</span></td>
                            <td><span class="text-muted small"><i class="fas fa-hourglass-half me-1"></i>Menunggu ACC piket</span></td>
                            <td class="text-end">
                                <form method="POST" action="{{ route('ajuan-absensi.hapus-ajuan', $a) }}" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus ajuan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i> Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $ajuan->onEachSide(1)->links() }}
    @endif
</div>
@endsection
