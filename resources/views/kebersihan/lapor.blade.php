@extends('layouts.app')

@section('title', 'Lapor Kebersihan - ' . $kelas)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-broom me-2"></i>Lapor Kelas {{ $kelas }} Kotor</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    <form method="POST" action="{{ route('kebersihan.simpan', $kelas) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Foto Kondisi Kelas</label>
            <input type="file" name="foto" accept="image/*" capture="environment" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Keterangan (opsional)</label>
            <input type="text" name="catatan" class="form-control" placeholder="contoh: banyak sampah di lantai">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Kirim Laporan</button>
            <a href="{{ route('kebersihan.kelas-grid') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
