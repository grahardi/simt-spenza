@extends('layouts.adminlte')

@section('title', 'Edit Absensi Guru')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Edit Absensi Guru - {{ $absen->guru->nama ?? '' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.absensi-guru.update', $absen) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tanggal" class="form-control" value="{{ $absen->tanggal->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="s" @selected($absen->status === 's')>Sakit</option>
                    <option value="i" @selected($absen->status === 'i')>Ijin</option>
                    <option value="a" @selected($absen->status === 'a')>Alfa</option>
                    <option value="d" @selected($absen->status === 'd')>Dispensasi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <input type="text" name="keterangan" class="form-control" value="{{ $absen->keterangan }}">
            </div>
            @if ($absen->foto)
                <div class="form-group">
                    <label class="d-block">Foto Surat</label>
                    <a href="{{ Storage::url($absen->foto) }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                        <i class="fas fa-image me-1"></i> Lihat Foto
                    </a>
                </div>
            @endif
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.absensi-guru.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
