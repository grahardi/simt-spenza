@extends('layouts.app')

@section('title', 'Profil')

@section('content')
<div class="p-4 bg-white rounded shadow">
    <h1 class="h5 mb-3">Profil</h1>
    @php $member = auth('member')->user(); @endphp
    <table class="table">
        <tr><th style="width:160px">Nama</th><td>{{ $member->nama }}</td></tr>
        <tr><th>Nomor ID</th><td>{{ $member->user }}</td></tr>
        <tr><th>Jabatan</th><td>{{ $member->jabatan }}</td></tr>
        <tr><th>Peran</th><td>{{ implode(', ', $member->roles()) ?: '-' }}</td></tr>
    </table>
</div>
@endsection
