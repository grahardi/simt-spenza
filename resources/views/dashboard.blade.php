@extends('layouts.app')

@section('title', 'Depan')

@section('content')
<div class="p-4 bg-white rounded shadow">
    <h1 class="h5 mb-1">Selamat datang, {{ auth('member')->user()->nama }}</h1>
    <p class="text-muted mb-0">
        Peran: {{ implode(', ', auth('member')->user()->roles()) ?: '-' }}
    </p>
</div>
@endsection
