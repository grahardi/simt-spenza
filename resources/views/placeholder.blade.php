@extends('layouts.app')

@section('title', $judul)

@section('content')
<div class="p-4 bg-white rounded shadow text-center text-muted">
    <i class="fas fa-hammer fa-2x mb-3"></i>
    <p class="mb-0">Modul "{{ $judul }}" belum dimigrasi ke Laravel. Menyusul.</p>
</div>
@endsection
