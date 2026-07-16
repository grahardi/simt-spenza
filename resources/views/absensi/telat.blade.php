@extends('layouts.app')

@section('title', 'Data Terlambat')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-clock me-2"></i>Data Siswa Terlambat</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($data->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada siswa terlambat pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>No</th><th>Nama</th><th>Kelas</th></tr></thead>
                <tbody>
                    @foreach ($data as $i => $row)
                        <tr>
                            <td>{{ $data->firstItem() + $i }}</td>
                            <td>{{ $row->siswa->nama_lengkap ?? '-' }}</td>
                            <td>{{ $row->siswa->kelas ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $data->onEachSide(1)->links() }}
    @endif
</div>
@endsection
