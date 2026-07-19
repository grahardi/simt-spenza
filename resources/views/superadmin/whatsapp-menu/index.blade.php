@extends('layouts.adminlte')

@section('title', 'Menu Bot WhatsApp')

@section('content')
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Menu Bot WhatsApp</h3>
        <a href="{{ route('superadmin.whatsapp-menu.create') }}" class="btn btn-primary btn-sm">
            <i class="fas fa-plus me-1"></i> Tambah Menu
        </a>
    </div>
    <div class="card-body">
        <p class="text-muted small">
            Kode <strong>registrasi</strong> dan <strong>absen</strong> bertipe "bawaan" - alurnya sudah
            terprogram (tidak bisa dihapus, kode & isi balasannya tidak bisa diubah lewat sini), hanya
            label/urutan/aktif-nonaktif yang bisa diedit. Menu bertipe "info" bebas ditambah/diubah/dihapus.
        </p>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th style="width:70px">Urutan</th>
                    <th>Kode</th>
                    <th>Label</th>
                    <th style="width:90px">Tipe</th>
                    <th style="width:80px">Status</th>
                    <th style="width:140px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($menu as $m)
                    <tr>
                        <td>{{ $m->urutan }}</td>
                        <td><code>{{ $m->kode }}</code></td>
                        <td>{{ $m->label }}</td>
                        <td>
                            <span class="badge {{ $m->isBawaan() ? 'badge-secondary' : 'badge-info' }}">{{ $m->tipe }}</span>
                        </td>
                        <td>
                            @if ($m->aktif)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('superadmin.whatsapp-menu.edit', $m) }}" class="btn btn-xs btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                            @unless ($m->isBawaan())
                                <form action="{{ route('superadmin.whatsapp-menu.destroy', $m) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Yakin hapus menu ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-outline-danger">
                                        <i class="fas fa-trash"></i> Hapus
                                    </button>
                                </form>
                            @endunless
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada menu.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
