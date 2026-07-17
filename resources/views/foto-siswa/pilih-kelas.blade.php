@extends('layouts.app')

@section('title', 'Foto Siswa')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-images me-2"></i>Foto Siswa</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="row g-2">
        <div class="col-md-8">
            <input type="text" name="cari" class="form-control" placeholder="Atau cari nama/nomor induk siswa langsung...">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search me-1"></i> Cari</button>
        </div>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($daftarKelas->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Data kelas belum ada di tabel <code>kelas</code>.
        </div>
    @else
        <div class="kelas-grid">
            @foreach ($daftarKelas as $k)
                @php
                    $tingkat = trim(explode('-', $k->nama_kelas)[0] ?? '');
                    $warna = match (true) {
                        str_starts_with($tingkat, '7') => 'kelas-7',
                        str_starts_with($tingkat, '8') => 'kelas-8',
                        str_starts_with($tingkat, '9') => 'kelas-9',
                        default => 'kelas-lain',
                    };
                @endphp
                <a href="{{ route('foto-siswa.kelas', $k->nama_kelas) }}" class="kelas-btn {{ $warna }}">
                    {{ str_replace(' - ', '', $k->nama_kelas) }}
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
