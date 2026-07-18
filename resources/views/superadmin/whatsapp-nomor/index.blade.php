@extends('layouts.adminlte')

@section('title', 'Nomor WhatsApp Terdaftar')

@section('content')
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header"><h3 class="card-title">Nomor WhatsApp Terdaftar</h3></div>
    <div class="card-body">
        <form method="GET" class="form-inline mb-3">
            <input type="text" name="cari" class="form-control mr-2" placeholder="Cari nama siswa atau nomor..." value="{{ request('cari') }}" style="max-width:300px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
        </form>

        <table class="table table-bordered table-striped">
            <thead><tr><th>No. Induk</th><th>Nama Siswa</th><th>Kelas</th><th>Nomor WhatsApp</th><th style="width:120px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($siswa as $s)
                    <tr>
                        <td>{{ $s->id_member }}</td>
                        <td>{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->kelas }}</td>
                        <td>{{ $s->whatsapp }}</td>
                        <td>
                            <form action="{{ route('superadmin.whatsapp-nomor.putuskan', $s) }}" method="POST"
                                  onsubmit="return confirm('Putuskan nomor WA dari {{ $s->nama_lengkap }}?')">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-unlink"></i> Putuskan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada nomor yang terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        {{ $siswa->onEachSide(1)->links() }}
    </div>
</div>
@endsection
