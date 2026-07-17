@extends($layout ?? 'layouts.app')

@php $prefix = request()->routeIs('jadwal-publik.*') ? 'jadwal-publik.' : 'jadwal.'; @endphp

@section('title', 'Pilih Kelas - Jadwal')

@section('content')
<div class="alert alert-danger py-2 mb-3"><i class="fas fa-exclamation-triangle me-1"></i> Ada beberapa jadwal yang masih perbaikan. Harap ditunggu.</div>
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal - Pilih Kelas</h1>
    </div>
    <a href="{{ route($prefix.'index') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Kembali
    </a>
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
                <a href="{{ route($prefix.'kelas', $k->nama_kelas) }}" class="kelas-btn {{ $warna }}">
                    {{ str_replace(' - ', '', $k->nama_kelas) }}
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
