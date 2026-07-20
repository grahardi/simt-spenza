@extends('layouts.app')

@section('title', 'Ajuan Surat')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-signature fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Ajuan Surat - {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route('ajuan-surat.sppd.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Ajukan SPPD
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada ajuan surat.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal Ajuan</th><th>Jenis</th><th>Judul/Perihal</th><th>Status</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $a)
                    <tr>
                        <td>{{ $a->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->labelJenis() }}</td>
                        <td>{{ $a->data['tema'] ?? '-' }}</td>
                        <td>
                            <span class="badge {{ $a->status === 'selesai' ? 'bg-success' : ($a->status === 'diproses' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                {{ $a->labelStatus() }}
                            </span>
                        </td>
                        <td class="text-end">
                            <a href="{{ route('ajuan-surat.sppd.edit', $a) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="fas fa-edit me-1"></i> Edit
                            </a>
                            @if ($a->file_pdf)
                                <a href="{{ Storage::url($a->file_pdf) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-file-word me-1"></i> Unduh Surat (Word)
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

{{ $daftar->onEachSide(1)->links() }}
@endsection
