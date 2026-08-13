@extends('layouts.app')

@section('title', 'Rekap Penerima Bansos')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Rekap Penerima Bansos</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2 align-items-center">
        <div class="col-md-4">
            <select name="kelas" class="form-select" onchange="this.form.submit()">
                <option value="">Semua Kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        @if (request('kelas'))
            <div class="col-md-2">
                <a href="{{ route('bansos.rekap') }}" class="btn btn-outline-secondary">Reset</a>
            </div>
        @endif
    </form>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($rekap->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada siswa yang diajukan Bansos.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>No</th><th>Nama Siswa</th><th>Kelas</th><th>Tanggal Diajukan</th></tr>
            </thead>
            <tbody>
                @foreach ($rekap as $i => $r)
                    <tr>
                        <td>{{ $rekap->firstItem() + $i }}</td>
                        <td>{{ $r->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $r->kelas }}</td>
                        <td>{{ $r->created_at->translatedFormat('d M Y') }}</td>
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
