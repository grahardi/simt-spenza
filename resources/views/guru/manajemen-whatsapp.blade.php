@extends('layouts.app')

@section('title', 'Manajemen WhatsApp')

@section('content')
@include('partials.menu-wali')

<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fab fa-whatsapp me-2"></i>Manajemen WhatsApp - {{ $guru->nama }}</h1>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada siswa yang terdaftar sebagai anak wali Bapak/Ibu.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>No. Induk</th><th>Nama Siswa</th><th>Kelas</th><th>Nomor WhatsApp Terdaftar</th></tr>
            </thead>
            <tbody>
                @foreach ($siswa as $s)
                    <tr style="background:{{ $s->jenis_kelamin === 'P' ? '#fde9ec' : '#e6f7ea' }};">
                        <td>{{ $s->id_member }}</td>
                        <td>{{ $s->nama_lengkap }}</td>
                        <td>{{ $s->kelas }}</td>
                        <td>
                            @forelse ($s->nomorWhatsapp as $nw)
                                <a href="https://wa.me/{{ $nw->nomor }}" target="_blank" class="btn btn-sm btn-success mb-1">
                                    <i class="fab fa-whatsapp me-1"></i> {{ $nw->label ?? 'Wali' }}
                                </a>
                            @empty
                                <span class="text-muted small">Belum ada nomor terdaftar</span>
                            @endforelse
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
