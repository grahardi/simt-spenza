@extends('layouts.adminlte')

@section('title', 'WA Guru Terdaftar')

@section('content')
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Nomor WhatsApp Guru Terdaftar</h3>
        <a href="{{ route('superadmin.whatsapp-guru.export-vcf') }}" class="btn btn-outline-success btn-sm">
            <i class="fas fa-address-card me-1"></i> Export Kontak (VCF)
        </a>
    </div>
    <div class="card-body">
        <p class="text-muted small">
            Hasil registrasi lewat fitur tersembunyi <code>regis-guru</code> di bot WhatsApp.
            Guru pakai fitur ini untuk lihat jadwal mengajar hari ini via chat (ketik <code>jadwal</code>).
        </p>

        <form method="GET" class="form-inline mb-3">
            <input type="text" name="cari" class="form-control mr-2" placeholder="Cari nama guru atau nomor..." value="{{ request('cari') }}" style="max-width:300px">
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Nama Guru</th><th>Mapel/Jabatan</th><th>Nomor WhatsApp</th><th style="width:120px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($nomor as $n)
                    <tr>
                        <td>{{ $n->guru->nama ?? '-' }}</td>
                        <td>{{ $n->guru->jabatan ?? '-' }}</td>
                        <td>{{ $n->nomor }}</td>
                        <td>
                            <form action="{{ route('superadmin.whatsapp-guru.putuskan', $n) }}" method="POST"
                                  onsubmit="return confirm('Putuskan nomor ini dari {{ $n->guru->nama ?? '' }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-unlink"></i> Putuskan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="text-center text-muted">Belum ada guru yang registrasi WA.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $nomor->onEachSide(1)->links() }}
    </div>
</div>
@endsection
