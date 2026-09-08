@extends('layouts.app')

@section('title', 'Pengaturan Tipe Denah')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-cogs me-2"></i>Pengaturan Tipe Denah</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-1"></i> Tipe menentukan arah zigzag urutan nomor kursi di denah. "Kiri" = baris ganjil dibalik, "Kanan" = baris genap dibalik.
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    @if ($daftarRuang->isEmpty())
        <p class="text-muted">Belum ada data ruang - import data kartu ujian terlebih dahulu.</p>
    @else
        <form method="POST" action="{{ route('kartu-ujian.simpan-pengaturan-denah') }}">
            @csrf
            @foreach ($daftarRuang as $ruang)
                <div class="mb-3 d-flex align-items-center gap-2">
                    <label class="form-label mb-0" style="width:100px;">Ruang {{ $ruang }}</label>
                    <input type="hidden" name="ruang[{{ $loop->index }}]" value="{{ $ruang }}">
                    <select name="tipe[{{ $loop->index }}]" class="form-select">
                        <option value="kiri" @selected(($pengaturan[$ruang]->tipe ?? 'kiri') === 'kiri')>Kiri</option>
                        <option value="kanan" @selected(($pengaturan[$ruang]->tipe ?? 'kiri') === 'kanan')>Kanan</option>
                    </select>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
        </form>
    @endif
</div>
@endsection
