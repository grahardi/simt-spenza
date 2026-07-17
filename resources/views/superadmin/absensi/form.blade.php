@extends('layouts.adminlte')

@section('title', 'Edit Absensi')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Edit Absensi - {{ $absen->siswa->nama_lengkap ?? '' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.absensi.update', $absen) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tgl_absen" class="form-control" value="{{ $absen->tgl_absen->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <select name="keterangan" class="form-control" required>
                    <option value="h" @selected($absen->keterangan === 'h')>Hadir</option>
                    <option value="s" @selected($absen->keterangan === 's')>Sakit</option>
                    <option value="i" @selected($absen->keterangan === 'i')>Ijin</option>
                    <option value="a" @selected($absen->keterangan === 'a')>Alfa</option>
                    <option value="d" @selected($absen->keterangan === 'd')>Dispensasi</option>
                </select>
            </div>
            <div class="form-group">
                <label>Catatan Tambahan</label>
                <input type="text" name="tambahan" class="form-control" value="{{ $absen->tambahan }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.absensi.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
