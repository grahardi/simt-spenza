@extends('layouts.app')

@section('title', 'Edit Pelanggaran')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-edit me-2"></i>Edit Pelanggaran - {{ $pelanggaran->siswa->nama_lengkap ?? '-' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow mx-auto" style="max-width:520px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('tatib.pelanggaran.update', $pelanggaran) }}">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label class="form-label">Tanggal Pelanggaran</label>
            <input type="date" name="tgl_pelanggaran" class="form-control" value="{{ old('tgl_pelanggaran', $pelanggaran->tgl_pelanggaran?->format('Y-m-d')) }}" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Kategori</label>
            <select name="kategori" class="form-select" required>
                @foreach (\App\Models\Pelanggaran::KATEGORI as $k)
                    <option value="{{ $k }}" @selected(old('kategori', $pelanggaran->kategori) === $k)>{{ $k }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Poin</label>
            <input type="number" name="poin" class="form-control" value="{{ old('poin', $pelanggaran->poin) }}" step="1" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan</label>
            <textarea name="keterangan" class="form-control" rows="3">{{ old('keterangan', $pelanggaran->keterangan) }}</textarea>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Perubahan</button>
            <a href="{{ route('tatib.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
