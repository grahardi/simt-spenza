@extends('layouts.adminlte')

@section('title', 'Nomor WhatsApp Terdaftar')

@section('content')
@if (session('status'))
    <div class="alert alert-info">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Nomor WhatsApp Terdaftar</h3>
        <div>
            <a href="{{ route('superadmin.whatsapp-nomor.export-vcf') }}" class="btn btn-outline-success btn-sm">
                <i class="fas fa-address-card me-1"></i> Export Kontak (VCF)
            </a>
            <a href="{{ route('superadmin.whatsapp-nomor.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus me-1"></i> Tambah Nomor
            </a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">1 siswa bisa punya sampai {{ \App\Models\SiswaWhatsapp::MAKSIMAL_PER_SISWA }} nomor (misal Ayah/Ibu/Wali).</p>

        <form method="GET" class="form-inline mb-3">
            <input type="text" name="cari" class="form-control mr-2" placeholder="Cari nama siswa atau nomor..." value="{{ request('cari') }}" style="max-width:300px">
            <select name="kelas" class="form-control mr-2" onchange="this.form.submit()">
                <option value="">Semua kelas</option>
                @foreach ($daftarKelas as $k)
                    <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Cari</button>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>No. Induk</th><th>Nama Siswa</th><th>Kelas</th><th>Nomor WhatsApp</th><th>Label</th><th style="width:120px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($nomor as $n)
                    <tr>
                        <td>{{ $n->siswa->id_member ?? '-' }}</td>
                        <td>{{ $n->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $n->siswa->kelas ?? '-' }}</td>
                        <td>{{ $n->nomor }}</td>
                        <td>{{ $n->label ?? '-' }}</td>
                        <td>
                            <form action="{{ route('superadmin.whatsapp-nomor.putuskan', $n) }}" method="POST"
                                  onsubmit="return confirm('Putuskan nomor ini dari {{ $n->siswa->nama_lengkap ?? '' }}?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger">
                                    <i class="fas fa-unlink"></i> Putuskan
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada nomor yang terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $nomor->onEachSide(1)->links() }}
    </div>
</div>
@endsection
