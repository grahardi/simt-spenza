@extends('layouts.adminlte')

@section('title', 'Kelola Akun')

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Kelola Akun Login</h3>
        <a href="{{ route('superadmin.akun.create') }}" class="btn btn-primary btn-sm"><i class="fas fa-plus me-1"></i> Buat Akun</a>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama/nomor ID..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Nomor ID</th><th>Nama</th><th>Terhubung Guru</th><th>Roles</th><th style="width:220px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($akun as $a)
                    <tr>
                        <td>{{ $a->user }}</td>
                        <td>{{ $a->nama }}</td>
                        <td>{{ $a->guru->nama ?? '-' }}</td>
                        <td>
                            @php
                                $warnaRole = [
                                    'admin' => ['#e6f1fb', '#185fa5'], 'walikelas' => ['#eeedfe', '#534ab7'],
                                    'tatib' => ['#fcebeb', '#a32d2d'], 'bk' => ['#fbeaf0', '#993556'],
                                    'guru' => ['#eaf3de', '#3b6d11'], 'keagamaan' => ['#eeedfe', '#534ab7'],
                                    'kebersihan' => ['#e1f5ee', '#0f6e56'], 'kepsek' => ['#faeeda', '#854f0b'],
                                    'adminsoal' => ['#faece7', '#993c1d'], 'tata_usaha' => ['#e6f1fb', '#185fa5'],
                                    'uks' => ['#fcebeb', '#a32d2d'], 'piket' => ['#eeedfe', '#534ab7'],
                                    'superadmin' => ['#faeeda', '#854f0b'],
                                ];
                            @endphp
                            @forelse ($a->roles() as $r)
                                @php [$bg, $fg] = $warnaRole[$r] ?? ['#f0f0f0', '#666']; @endphp
                                <span class="badge" style="background:{{ $bg }};color:{{ $fg }};font-weight:600;">{{ $r }}</span>
                            @empty
                                <span class="text-muted">-</span>
                            @endforelse
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('superadmin.akun.edit', $a) }}" class="btn btn-xs btn-outline-info"><i class="fas fa-user-shield"></i> Kelola</a>
                            <form action="{{ route('superadmin.akun.login-sebagai', $a) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Login sebagai {{ $a->nama }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-outline-warning"><i class="fas fa-user-secret"></i> Login Sebagai</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada akun.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $akun->onEachSide(1)->links() }}
    </div>
</div>
@endsection
