@extends('layouts.app')

@section('title', 'Lapor Pelanggaran - ' . $siswa->nama_lengkap)

@section('content')
@include('partials.menu-kesiswaan')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-gavel me-2"></i>Lapor Pelanggaran</h1>
</div>

<div class="p-4 bg-white rounded shadow mx-auto" style="max-width:520px;">
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        @if ($siswa->foto_url)
            <img src="{{ $siswa->foto_url }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
        @else
            <span class="foto-siswa-placeholder" style="width:56px;height:56px;font-size:18px;">{{ $siswa->initials() }}</span>
        @endif
        <div>
            <div class="fw-bold">{{ $siswa->nama_lengkap }}</div>
            <div class="text-muted small">No. Induk {{ $siswa->id_member }} &middot; Kelas {{ $siswa->kelas }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('tatib.simpan', $siswa) }}">
        @csrf
        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">Jenis Pelanggaran</label>
            <div class="kategori-pilih">
                @foreach ([
                    'Peringatan' => 'blue',
                    'Ringan' => 'green',
                    'Sedang' => 'amber',
                    'Berat' => 'red',
                ] as $kat => $warna)
                    <input type="radio" name="kategori" value="{{ $kat }}" id="kat{{ $kat }}" class="kategori-radio" @checked($loop->first) required>
                    <label for="kat{{ $kat }}" class="kategori-label bg-{{ $warna }}">{{ $kat }}</label>
                @endforeach
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Poin</label>
            <input type="number" name="poin" class="form-control" placeholder="contoh: 10" required>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Keterangan Pelanggaran</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Ceritakan kejadiannya..."></textarea>
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-save me-1"></i> Simpan Laporan</button>
            <a href="{{ route('tatib.cari') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
