@extends('layouts.adminlte')

@section('title', 'Log Login')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Log Login</h3>
    </div>
    <div class="card-body">
        <table class="table table-striped">
            <thead><tr><th style="width:180px">Waktu Login</th><th>Nama</th><th style="width:120px">Nomor ID</th></tr></thead>
            <tbody>
                @forelse ($log as $l)
                    <tr>
                        <td class="small">{{ $l->created_at->translatedFormat('d M Y H:i:s') }}</td>
                        <td>{{ $l->member->nama ?? '-' }}</td>
                        <td>{{ $l->member->user ?? '-' }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada riwayat login.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $log->onEachSide(1)->links() }}
    </div>
</div>
@endsection
