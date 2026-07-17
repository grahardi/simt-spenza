@extends('layouts.app')

@section('title', 'RPP Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-book me-2"></i>RPP Semua Guru</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($rpp->isEmpty())
        <div class="text-muted text-center py-4">Belum ada RPP yang diupload.</div>
    @else
        <table class="table table-striped align-middle">
            <thead><tr><th>Guru</th><th>Bulan</th><th>Tanggal</th><th>File</th><th>Status</th><th></th></tr></thead>
            <tbody>
                @foreach ($rpp as $r)
                    <tr>
                        <td>{{ $r->guru->nama ?? '-' }}</td>
                        <td>{{ $r->bulan }}</td>
                        <td>{{ $r->tanggal->translatedFormat('d M Y') }}</td>
                        <td><a href="{{ Storage::url($r->namafile) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf"></i></a></td>
                        <td>
                            @if ($r->sudahDisetujui())
                                <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Disetujui</span>
                            @else
                                <span class="badge-status" style="background:#faeeda;color:#854f0b;">Menunggu</span>
                            @endif
                        </td>
                        <td>
                            @if (!$r->sudahDisetujui())
                                <form method="POST" action="{{ route('rpp.setujui', $r) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success"><i class="fas fa-check me-1"></i> Setujui</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $rpp->onEachSide(1)->links() }}
    @endif
</div>
@endsection
