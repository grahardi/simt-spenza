@extends('layouts.adminlte')

@section('title', 'Tambah Nomor WhatsApp')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Tambah Nomor WhatsApp</h3></div>
    <div class="card-body">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('superadmin.whatsapp-nomor.store') }}">
            @csrf
            <div class="form-group">
                <label>Nomor Induk Siswa</label>
                <input type="number" name="no_induk" class="form-control" value="{{ old('no_induk') }}" required>
            </div>
            <div class="form-group">
                <label>Nomor WhatsApp</label>
                <input type="text" name="nomor" class="form-control" value="{{ old('nomor') }}" placeholder="contoh: 6281234567890" required>
            </div>
            <div class="form-group">
                <label>Label (opsional)</label>
                <select name="label" class="form-control">
                    <option value="">- Tidak ditentukan -</option>
                    <option value="Ayah" {{ old('label') === 'Ayah' ? 'selected' : '' }}>Ayah</option>
                    <option value="Ibu" {{ old('label') === 'Ibu' ? 'selected' : '' }}>Ibu</option>
                    <option value="Wali" {{ old('label') === 'Wali' ? 'selected' : '' }}>Wali</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.whatsapp-nomor.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
