@extends('layouts.app')

@section('title', 'Ajukan SPPD')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-file-signature me-2"></i>Ajukan SPPD</h1>
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
        Data diri (nama, NIP, pangkat, jabatan) diambil otomatis dari data kepegawaian Bapak/Ibu.
    </p>

    <form method="POST" action="{{ route('ajuan-surat.sppd.store') }}">
        @csrf
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label">Dasar Penugasan</label>
                <textarea name="dasar" class="form-control" rows="2" placeholder="contoh: Berdasarkan surat undangan MGMP Kabupaten Malang..., tanggal ..., No. ... perihal ...">{{ old('dasar') }}</textarea>
            </div>
            <div class="col-md-4">
                <label class="form-label">Hari</label>
                <input type="text" name="hari" class="form-control" value="{{ old('hari') }}" placeholder="contoh: Rabu" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Berangkat</label>
                <input type="date" name="tanggal" class="form-control" value="{{ old('tanggal') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Tanggal Kembali (opsional)</label>
                <input type="date" name="tanggal_selesai" class="form-control" value="{{ old('tanggal_selesai') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Mulai</label>
                <input type="time" name="jam_mulai" class="form-control" value="{{ old('jam_mulai') }}" required>
            </div>
            <div class="col-md-4">
                <label class="form-label">Jam Selesai (opsional)</label>
                <input type="time" name="jam_selesai" class="form-control" value="{{ old('jam_selesai') }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Total Hari</label>
                <input type="number" name="total_hari" class="form-control" value="{{ old('total_hari', 1) }}" min="1">
            </div>
            <div class="col-12">
                <label class="form-label">Tempat Kegiatan</label>
                <input type="text" name="tempat" class="form-control" value="{{ old('tempat') }}" placeholder="contoh: SMP Negeri 1 Tajinan" required>
            </div>
            <div class="col-12">
                <label class="form-label">Tempat Tujuan (lengkap)</label>
                <input type="text" name="tempat_tujuan" class="form-control" value="{{ old('tempat_tujuan') }}" placeholder="contoh: SMP Negeri 1 Tajinan, Jl. ..." required>
            </div>
            <div class="col-12">
                <label class="form-label">Tema / Perihal Kegiatan</label>
                <input type="text" name="tema" class="form-control" value="{{ old('tema') }}" placeholder="contoh: Pertemuan Rutin MGMP Informatika" required>
            </div>
            <div class="col-12">
                <label class="form-label">Maksud Perjalanan Dinas</label>
                <input type="text" name="maksud" class="form-control" value="{{ old('maksud') }}" placeholder="contoh: Menghadiri undangan MGMP" required>
            </div>
        </div>

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fas fa-paper-plane me-1"></i> Kirim Ajuan</button>
            <a href="{{ route('ajuan-surat.index') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
