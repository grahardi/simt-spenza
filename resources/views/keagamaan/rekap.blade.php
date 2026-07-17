@extends('layouts.app')

@section('title', 'Rekap Keagamaan')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-pray me-2"></i>Rekap Pelanggaran Keagamaan</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($lapor->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada laporan pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Pelapor</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lapor as $i => $l)
                        <tr>
                            <td>{{ $lapor->firstItem() + $i }}</td>
                            <td>{{ $l->siswa->nama_lengkap ?? '-' }}</td>
                            <td>{{ $l->siswa->kelas ?? '-' }}</td>
                            <td><span class="badge-status" style="background:#eeedfe;color:#534ab7;">{{ $l->label() }}</span></td>
                            <td class="small">{{ $l->keterangan }}</td>
                            <td>{{ $l->pelapor->nama ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $lapor->onEachSide(1)->links() }}
    @endif
</div>
@endsection
