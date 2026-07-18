@extends('layouts.adminlte')

@section('title', 'Edit Template Bot')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header"><h3 class="card-title">Edit Balasan: <code>{{ $item->kode }}</code></h3></div>
    <div class="card-body">
        <p class="text-muted small">{{ $item->keterangan }}</p>

        <form method="POST" action="{{ route('superadmin.whatsapp-template.update', $item) }}">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label>Teks Balasan</label>
                <textarea name="teks" class="form-control" rows="8" required>{{ old('teks', $item->teks) }}</textarea>
                <small class="text-muted">
                    Boleh pakai *bold* format WhatsApp. Placeholder di teks ini (kalau ada) jangan dihapus,
                    contoh <code>{nama}</code> akan diganti nama siswa asli.
                </small>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.whatsapp-template.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
