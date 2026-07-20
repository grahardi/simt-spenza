@extends('layouts.app')

@section('title', 'Guru Wali - ' . $guru->nama)

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-user-friends fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Anak Wali - {{ $guru->nama }}</h1>
    </div>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada siswa yang terdaftar sebagai anak wali Bapak/Ibu.
        </div>
    @else
        <p class="text-muted small mb-3">Total {{ $siswa->count() }} siswa.</p>
        <div class="d-flex flex-column gap-2">
            @foreach ($siswa as $s)
                <a href="{{ route('siswa.profil', $s) }}" class="siswa-baris {{ $s->jenis_kelamin === 'P' ? 'siswa-baris-p' : 'siswa-baris-l' }}">
                    <span class="siswa-no-kecil">{{ $loop->iteration }}</span>
                    <span class="siswa-induk-kecil">{{ $s->id_member }}</span>
                    <span class="siswa-nama-kecil">{{ $s->nama_lengkap }}</span>
                    <span class="siswa-kelas-kecil">{{ $s->kelas }}</span>
                </a>
            @endforeach
        </div>
    @endif
</div>
@endsection
