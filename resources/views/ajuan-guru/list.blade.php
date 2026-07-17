@extends('layouts.app')

@section('title', 'List Ajuan Guru')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-list fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">List Ajuan Guru (Kelas Kosong)</h1>
    </div>
    <a href="{{ route('ajuan-guru.form') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Lapor Baru
    </a>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($laporan->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada laporan kelas kosong.
        </div>
    @else
        <table class="table table-striped align-middle">
            <thead><tr><th>Tanggal</th><th>Guru</th><th>Kelas</th><th>Jam</th><th>Keterangan</th><th>Status/Alasan</th></tr></thead>
            <tbody>
                @foreach ($laporan as $l)
                    <tr>
                        <td>{{ $l->tgl_warning?->translatedFormat('d M Y') }}</td>
                        <td>{{ $l->guru->nama ?? '-' }}</td>
                        <td>{{ $l->kelas }}</td>
                        <td>{{ $l->jam }}</td>
                        <td class="small">{{ $l->keterangan }}</td>
                        <td>
                            @if ($l->belumDitanggapi())
                                <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Menunggu konfirmasi</span>
                            @else
                                <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">{{ $l->aksi }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        {{ $laporan->onEachSide(1)->links() }}
    @endif
</div>
@endsection
