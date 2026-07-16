@extends('layouts.app')

@section('title', 'Pilih Kelas')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-inbox fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Ajukan Absensi - Pilih Kelas</h1>
    </div>
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
                <a href="{{ route('ajuan-absensi.ajukan', $k->nama_kelas) }}" class="kelas-btn {{ $warna }}">
                    {{ str_replace(' - ', '', $k->nama_kelas) }}
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
