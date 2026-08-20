@extends('layouts.app')

@section('title', 'Pelanggaran Keagamaan - ' . $kelas)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-mosque fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Pelanggaran Keagamaan - {{ $kelas }}</h1>
    </div>
    <a href="{{ route('pelanggaran-keagamaan.pilih-kelas') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Ganti Kelas
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<p class="text-muted small mb-3">Catatan untuk hari ini, {{ now('Asia/Jakarta')->translatedFormat('d F Y') }}.</p>

<div class="bg-white rounded shadow overflow-hidden">
    <div class="table-responsive">
    <table class="table table-striped mb-0 align-middle">
        <thead>
            <tr><th>Nama</th><th>Status Sekarang</th><th class="text-end">Aksi</th></tr>
        </thead>
        <tbody>
            @foreach ($siswa as $s)
                @php $catatan = $sudahDicatatHariIni[$s->id_member] ?? null; @endphp
                <tr>
                    <td>{{ $s->nama_lengkap }}</td>
                    <td>
                        @if ($catatan)
                            <span class="badge {{ $catatan->status === 'kabur' ? 'bg-danger' : ($catatan->status === 'halangan' ? 'bg-warning text-dark' : 'bg-info text-dark') }}">
                                {{ $catatan->labelStatus() }}
                            </span>
                            <form method="POST" action="{{ route('pelanggaran-keagamaan.hapus', $catatan) }}" class="d-inline ms-1" onsubmit="return confirm('Hapus catatan ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-link text-danger p-0"><i class="fas fa-times"></i></button>
                            </form>
                        @else
                            <span class="text-muted small">-</span>
                        @endif
                    </td>
                    <td class="text-end">
                        <form method="POST" action="{{ route('pelanggaran-keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="ijin">
                            <button type="submit" class="btn btn-sm btn-outline-info">Ijin</button>
                        </form>
                        <form method="POST" action="{{ route('pelanggaran-keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="halangan">
                            <button type="submit" class="btn btn-sm btn-outline-warning">Halangan</button>
                        </form>
                        <form method="POST" action="{{ route('pelanggaran-keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="status" value="kabur">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Kabur</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    </div>
</div>
@endsection
