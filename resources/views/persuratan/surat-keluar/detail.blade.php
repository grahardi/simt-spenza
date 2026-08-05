@extends('layouts.app')

@section('title', $surat->perihal ?? 'Detail Surat Keluar')

@section('content')
@include('persuratan._menu')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-paper-plane me-2"></i>{{ $surat->perihal }}</h1>
    <p class="mb-0 small text-white-50">{{ $surat->tanggal_surat->translatedFormat('d F Y') }}</p>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow mb-3" style="max-width:640px;">
    <table class="table table-sm mb-0">
        <tr><td width="180">Kode Surat</td><td><code>{{ $surat->kode_surat }}</code></td></tr>
        <tr><td>Kategori</td><td>{{ $surat->kategori->nama ?? '-' }}</td></tr>
        <tr><td>Tujuan</td><td>{{ $surat->tujuan_surat }}</td></tr>
        <tr><td>Perihal</td><td>{{ $surat->perihal }}</td></tr>
    </table>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:640px;">
    <h6 class="mb-3">Aksi</h6>
    <div class="d-flex flex-wrap gap-2">
        @if ($surat->lampiran)
            <a href="{{ Storage::url($surat->lampiran) }}" target="_blank" class="btn btn-outline-primary">
                <i class="fas fa-paperclip me-1"></i> Lihat Lampiran
            </a>
        @endif
        <a href="{{ route('surat-keluar.edit', $surat) }}" class="btn btn-outline-secondary">
            <i class="fas fa-edit me-1"></i> Edit
        </a>
        <form method="POST" action="{{ route('surat-keluar.destroy', $surat) }}" onsubmit="return confirm('Yakin hapus surat ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-outline-danger">
                <i class="fas fa-trash me-1"></i> Hapus
            </button>
        </form>
    </div>

    <a href="{{ route('surat-keluar.index') }}" class="btn btn-link mt-3 ps-0">
        <i class="fas fa-arrow-left me-1"></i> Kembali ke List
    </a>
</div>
@endsection
