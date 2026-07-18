@extends('layouts.adminlte')

@section('title', $item->exists ? 'Edit Menu Bot' : 'Tambah Menu Bot')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header"><h3 class="card-title">{{ $item->exists ? 'Edit Menu Bot' : 'Tambah Menu Bot' }}</h3></div>
    <div class="card-body">
        @if ($item->exists && $item->isBawaan())
            <div class="alert alert-warning small">
                Menu ini bertipe <strong>bawaan</strong> - kode <code>{{ $item->kode }}</code> dan alurnya
                sudah terprogram di sistem, tidak bisa diubah. Hanya label, urutan, dan status aktif yang
                bisa diedit di sini.
            </div>
        @endif

        <form method="POST" action="{{ $item->exists ? route('superadmin.whatsapp-menu.update', $item) : route('superadmin.whatsapp-menu.store') }}">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            @if (! $item->exists || ! $item->isBawaan())
                <div class="form-group">
                    <label>Kode (kata yang harus diketik PERSIS oleh user, huruf kecil)</label>
                    <input type="text" name="kode" class="form-control" value="{{ old('kode', $item->kode) }}"
                           placeholder="contoh: jadwal, kontak, prestasi" {{ $item->exists ? 'readonly' : '' }} required>
                    @error('kode') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            @endif

            <div class="form-group">
                <label>Label (penjelasan singkat, tampil di daftar menu)</label>
                <input type="text" name="label" class="form-control" value="{{ old('label', $item->label) }}" required>
            </div>

            @if (! $item->exists || ! $item->isBawaan())
                <div class="form-group">
                    <label>Balasan (teks yang dikirim kalau user ketik kode ini)</label>
                    <textarea name="balasan" class="form-control" rows="5" required>{{ old('balasan', $item->balasan) }}</textarea>
                    <small class="text-muted">Bisa pakai *bold* seperti format WhatsApp biasa.</small>
                </div>
            @endif

            <div class="form-group">
                <label>Urutan tampil di menu</label>
                <input type="number" name="urutan" class="form-control" value="{{ old('urutan', $item->urutan) }}" min="0">
            </div>

            <div class="form-check mb-3">
                <input type="checkbox" name="aktif" value="1" class="form-check-input" id="aktif" {{ old('aktif', $item->aktif) ? 'checked' : '' }}>
                <label class="form-check-label" for="aktif">Aktif (tampil di menu bot)</label>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.whatsapp-menu.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
