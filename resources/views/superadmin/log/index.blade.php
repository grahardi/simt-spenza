@extends('layouts.adminlte')

@section('title', 'Log Aktivitas')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Log Aktivitas</h3>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs mb-3">
            @foreach (['absensi' => 'Absensi', 'pelanggaran' => 'Pelanggaran', 'keterlambatan' => 'Keterlambatan', 'password' => 'Password', 'sistem' => 'Sistem', 'lainnya' => 'Lainnya'] as $k => $label)
                <li class="nav-item">
                    <a href="{{ route('superadmin.log.index', ['kategori' => $k]) }}" class="nav-link {{ $kategori === $k ? 'active' : '' }}">
                        {{ $label }} <span class="badge badge-secondary">{{ $jumlahPerKategori[$k] ?? 0 }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th style="width:160px">Waktu</th><th style="width:200px">Oleh</th><th>Aktivitas</th></tr></thead>
            <tbody>
                @forelse ($log as $l)
                    <tr>
                        <td class="small">{{ $l->created_at->translatedFormat('d M Y H:i') }}</td>
                        <td>{{ $l->member->nama ?? '(sistem)' }}</td>
                        <td>{{ $l->aksi }}</td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada log di kategori ini.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $log->onEachSide(1)->links() }}
    </div>
</div>
@endsection
