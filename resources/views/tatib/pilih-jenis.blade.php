@extends('layouts.app')

@section('title', 'Lapor - ' . $siswa->nama_lengkap)

@section('content')
@include('partials.menu-kesiswaan')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-gavel me-2"></i>Lapor - {{ $siswa->nama_lengkap }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow mx-auto" style="max-width:600px;">
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        @if ($siswa->foto_url)
            <img src="{{ $siswa->foto_url }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
        @else
            <span class="foto-siswa-placeholder" style="width:56px;height:56px;font-size:18px;">{{ $siswa->initials() }}</span>
        @endif
        <div>
            <div class="fw-bold">{{ $siswa->nama_lengkap }}</div>
            <div class="text-muted small">No. Induk {{ $siswa->id_member }} &middot; Kelas {{ $siswa->kelas }}</div>
        </div>
    </div>

    <p class="text-muted small mb-3">Pilih jenis tindakan:</p>
    <div class="row g-3">
        <div class="col-md-4">
            <button type="button" class="btn btn-outline-primary w-100 h-100 py-3" data-bs-toggle="modal" data-bs-target="#modalNotifWali">
                <i class="fas fa-bell fa-lg d-block mb-2"></i> Notif Wali Kelas
            </button>
        </div>
        <div class="col-md-4">
            <button type="button" class="btn btn-outline-success w-100 h-100 py-3" data-bs-toggle="modal" data-bs-target="#modalAjukanBk">
                <i class="fas fa-hands-helping fa-lg d-block mb-2"></i> Ajukan BK
            </button>
        </div>
        <div class="col-md-4">
            <a href="{{ route('tatib.lapor-pelanggaran', $siswa) }}" class="btn btn-outline-danger w-100 h-100 py-3">
                <i class="fas fa-gavel fa-lg d-block mb-2"></i> Ajukan Pelanggaran
            </a>
        </div>
    </div>
</div>

<div class="modal fade" id="modalNotifWali" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tatib.notif-walikelas', $siswa) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Notif Wali Kelas - {{ $siswa->nama_lengkap }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Alasan</label>
                <textarea name="alasan" class="form-control" rows="2" placeholder="contoh: Tidak masuk lebih dari 3 kali" required></textarea>
                <p class="text-muted small mt-2 mb-0">Wali kelas akan menerima notifikasi WhatsApp (kalau sudah registrasi) dan muncul di daftar pelanggaran kelasnya.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Kirim Notif</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="modalAjukanBk" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('tatib.ajukan-bk', $siswa) }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Ajukan BK - {{ $siswa->nama_lengkap }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label">Alasan</label>
                <textarea name="alasan" class="form-control" rows="2" placeholder="contoh: Perlu konseling terkait sering tidak masuk" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-success">Ajukan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
