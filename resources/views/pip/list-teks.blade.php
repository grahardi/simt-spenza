@extends('layouts.app')

@section('title', 'List PIP untuk WhatsApp')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-clipboard me-2"></i>List PIP untuk WhatsApp - Kelas {{ $kelasWali }}</h1>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Status Cair</label>
        <select name="status" class="form-select" style="max-width:200px" onchange="this.form.submit()">
            <option value="" @selected($status === '')>Semua</option>
            <option value="sudah" @selected($status === 'sudah')>Sudah Cair</option>
            <option value="belum" @selected($status === 'belum')>Belum Cair</option>
        </select>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($daftar->isEmpty())
        <p class="text-muted mb-0">Tidak ada data untuk filter ini.</p>
    @else
        <p class="text-muted small">Format: Nama / Kelas - Status. Klik kotak di bawah lalu Ctrl+A, Ctrl+C untuk copy semua ke WhatsApp.</p>
        <textarea id="teksSalin" class="form-control" rows="{{ min($daftar->count() + 1, 20) }}" onclick="this.select()" readonly>{{ $teks }}</textarea>
        <button type="button" class="btn btn-outline-primary btn-sm mt-2" onclick="salinTeks()">
            <i class="fas fa-copy me-1"></i> Copy ke Clipboard
        </button>
        <span id="pesanCopy" class="text-success small ms-2" style="display:none;">Berhasil disalin!</span>
    @endif
</div>

<script>
function salinTeks() {
    const el = document.getElementById('teksSalin');
    el.select();
    navigator.clipboard.writeText(el.value).then(function () {
        const pesan = document.getElementById('pesanCopy');
        pesan.style.display = 'inline';
        setTimeout(() => pesan.style.display = 'none', 2000);
    });
}
</script>
@endsection
