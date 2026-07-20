@extends('layouts.app')

@section('title', 'Ajuan Surat (Tata Usaha)')

@section('content')
@include('persuratan._menu')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-signature me-2"></i>Ajuan Surat</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="d-flex gap-2 mb-3">
    <a href="{{ route('surat-tu.index', ['status' => 'menunggu']) }}" class="tab-tahun {{ $status === 'menunggu' ? 'active' : '' }}" style="text-decoration:none;display:inline-block;">Menunggu</a>
    <a href="{{ route('surat-tu.index', ['status' => 'selesai']) }}" class="tab-tahun {{ $status === 'selesai' ? 'active' : '' }}" style="text-decoration:none;display:inline-block;">Selesai</a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada ajuan surat berstatus "{{ $status }}".
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal Ajuan</th><th>Guru</th><th>Jenis</th><th>Perihal</th><th></th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $a)
                    <tr>
                        <td>{{ $a->created_at->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->guru->nama ?? '-' }}</td>
                        <td>{{ $a->labelJenis() }}</td>
                        <td>{{ $a->data['tema'] ?? '-' }}</td>
                        <td class="text-end">
                            <a href="{{ route('surat-tu.show', $a) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-eye me-1"></i> Lihat Detail
                            </a>
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
