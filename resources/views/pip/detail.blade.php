@extends('layouts.app')

@section('title', 'Detail PIP - ' . ($p->siswa->nama_lengkap ?? ''))

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hand-holding-usd me-2"></i>{{ $p->siswa->nama_lengkap ?? '-' }}</h1>
    <p class="mb-0 small text-white-50">Kelas {{ $p->siswa->kelas ?? '-' }}</p>
</div>

<div class="p-4 bg-white rounded shadow">
    <table class="table table-sm">
        <tr><td width="220">Nominal</td><td>Rp {{ number_format($p->nominal ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>No. Rekening</td><td>{{ $p->no_rekening }}</td></tr>
        <tr><td>Tahap</td><td>{{ $p->tahap_id }}</td></tr>
        <tr><td>Nomor SK</td><td>{{ $p->nomor_sk }}</td></tr>
        <tr><td>Tanggal SK</td><td>{{ $p->tanggal_sk?->translatedFormat('d F Y') ?? '-' }}</td></tr>
        <tr><td>Nama Rekening</td><td>{{ $p->nama_rekening }}</td></tr>
        <tr><td>Tanggal Cair</td><td>{{ $p->tanggal_cair?->translatedFormat('d F Y') ?? '-' }}</td></tr>
        <tr><td>Status Cair</td><td>{{ $p->status_cair }}</td></tr>
        <tr><td>No. KIP</td><td>{{ $p->no_kip ?? '-' }}</td></tr>
        <tr><td>No. KKS</td><td>{{ $p->no_kks ?? '-' }}</td></tr>
        <tr><td>No. KPS</td><td>{{ $p->no_kps ?? '-' }}</td></tr>
        <tr><td>Virtual Account</td><td>{{ $p->virtual_acc }}</td></tr>
        <tr><td>Nama Kartu</td><td>{{ $p->nama_kartu ?? '-' }}</td></tr>
        <tr><td>Semester</td><td>{{ $p->semester_id }}</td></tr>
        <tr><td>Layak PIP</td><td>{{ $p->layak_pip }}</td></tr>
        <tr><td>Keterangan Pencairan</td><td>{{ $p->keterangan_pencairan }}</td></tr>
        <tr><td>Confirmation Text</td><td>{{ $p->confirmation_text ?? '-' }}</td></tr>
        <tr><td>Tahap Keterangan</td><td>{{ $p->tahap_keterangan }}</td></tr>
        <tr><td>Nama Pengusul</td><td>{{ $p->nama_pengusul }}</td></tr>
    </table>

    <a href="{{ route('pip.index') }}" class="btn btn-outline-secondary">Kembali</a>
</div>
@endsection
