@extends('layouts.app')

@php $editMode = isset($ajuan); @endphp
@section('title', $editMode ? 'Edit Ajuan SPPD' : 'Ajukan SPPD')

@php
    $isi = fn ($field, $default = '') => old($field, $editMode ? ($ajuan->data[$field] ?? $default) : $default);
@endphp

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-signature me-2"></i>{{ $editMode ? 'Edit Ajuan SPPD' : 'Ajukan SPPD' }}</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:640px;">
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="text-muted small">
        Surat Tugas &amp; Surat Perjalanan Dinas akan dibuat otomatis oleh sistem berdasarkan data ini, setelah disetujui Tata Usaha.
        Data diri (nama, NIP, pangkat, jabatan) diambil otomatis dari data kepegawaian Bapak/Ibu. Hari dihitung otomatis dari tanggal berangkat.
        @if ($editMode)
            <br><strong>Ajuan ini bisa diedit kapan saja, termasuk setelah surat sudah dibuat - tinggal generate ulang kalau ada perubahan.</strong>
        @endif
    </p>

    <form method="POST" action="{{ $editMode ? route('ajuan-surat.sppd.update', $ajuan) : route('ajuan-surat.sppd.store') }}" enctype="multipart/form-data">
        @csrf
        @if ($editMode) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Berkas Pendukung <span class="text-muted">(undangan/surat penunjukan, jadi dasar penugasan)</span></label>
                <input type="file" name="berkas_pendukung" accept="image/*,.pdf" class="form-control">
                <small class="text-muted">
                    Boleh gambar atau PDF.
                    @if ($editMode && $ajuan->file_pendukung) Sudah ada berkas terupload - upload baru untuk mengganti. @endif
                </small>
            </div>
            <div class="col-12">
                <label class="form-label">Nama/Isian Surat Undangan</label>
                <input type="text" name="isian_form" class="form-control" value="{{ $isi('isian_form') }}" placeholder="contoh: MGMP Kabupaten Malang Mapel Informatika dan KKA (MGMP IFKKA)" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Tanggal Surat Undangan (opsional)</label>
                <input type="date" name="tanggal_dasar" class="form-control" value="{{ $isi('tanggal_dasar') }}">
            </div>
            <div class="col-md-6">
                <label class="form-label">Nomor Surat Undangan (opsional)</label>
                <input type="text" name="nomor_surat_dasar" class="form-control" value="{{ $isi('nomor_surat_dasar') }}" placeholder="contoh: 02/012/FKKA-SMP.KABmlg/VII/2026">
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Berangkat</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $isi('tanggal') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Kembali (opsional)</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ $isi('tanggal_selesai') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Hari</label>
                <input type="number" name="total_hari" class="form-control" value="{{ $isi('total_hari', 1) }}" min="1">
            </div>
            <div class="col-md-6">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" value="{{ $isi('jam_mulai') }}" required>
            </div>
            <div class="col-md-6">
                <label class="form-label">Jam Selesai (opsional)</label>
                <input type="time" name="jam_selesai" class="form-control" value="{{ $isi('jam_selesai') }}">
            </div>
            <div class="col-12">
                <label class="form-label">Tempat Tujuan</label>
                <input type="text" name="tempat_tujuan" class="form-control" value="{{ $isi('tempat_tujuan') }}" placeholder="contoh: SMP Negeri 1 Tajinan, Jl. ..." required>
            </div>
            <div class="col-12">
                <label class="form-label">Tema / Perihal Kegiatan</label>
                <input type="text" name="tema" class="form-control" value="{{ $isi('tema') }}" placeholder="contoh: Pertemuan Rutin MGMP Informatika" required>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-{{ $editMode ? 'save' : 'paper-plane' }} me-1"></i> {{ $editMode ? 'Simpan Perubahan' : 'Kirim Ajuan' }}
            </button>
            <a href="{{ $editMode ? ((auth('member')->user()->hasRole('tata_usaha') || auth('member')->user()->hasRole('kepsek')) ? route('surat-tu.show', $ajuan) : route('ajuan-surat.index')) : route('ajuan-surat.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
