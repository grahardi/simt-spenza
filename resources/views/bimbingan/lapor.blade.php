@extends('layouts.app')

@section('title', 'Bimbingan - ' . $siswa->nama_lengkap)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hands-helping me-2"></i>Bimbingan - {{ $siswa->nama_lengkap }}</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:520px;">
    <p class="text-muted small mb-3">
        No. Induk {{ $siswa->id_member }} &middot; Kelas {{ $siswa->kelas }}
    </p>

    <form method="POST" action="{{ route('bimbingan.simpan', $siswa) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Jenis</label>
            @foreach (\App\Models\Bimbingan::KATEGORI as $kat)
                <div class="form-check">
                    <input type="radio" name="kategori" value="{{ $kat }}" class="form-check-input" id="kat{{ $kat }}" @checked($loop->first) required>
                    <label class="form-check-label" for="kat{{ $kat }}">{{ $kat }}</label>
                </div>
            @endforeach
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan Pendampingan</label>
            <textarea name="keterangan" class="form-control" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Aksi Selanjutnya</label>
            <select name="tindakan" class="form-select" required>
                @foreach (\App\Models\Bimbingan::TINDAKAN as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Foto (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="form-control">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('bimbingan.cari') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
