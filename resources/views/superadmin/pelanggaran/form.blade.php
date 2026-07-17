@extends('layouts.adminlte')

@section('title', 'Edit Pelanggaran')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Edit Pelanggaran - {{ $pelanggaran->siswa->nama_lengkap ?? '' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.pelanggaran.update', $pelanggaran) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tgl_pelanggaran" class="form-control" value="{{ $pelanggaran->tgl_pelanggaran->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Kategori</label>
                <select name="kategori" class="form-control" required>
                    @foreach (['Peringatan','Ringan','Sedang','Berat'] as $k)
                        <option value="{{ $k }}" @selected($pelanggaran->kategori === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="keterangan" class="form-control" rows="3">{{ $pelanggaran->keterangan }}</textarea>
            </div>
            <div class="form-group">
                <label>Poin</label>
                <input type="number" name="poin" class="form-control" value="{{ $pelanggaran->poin }}">
            </div>
            <div class="form-group">
                <label>Penanganan</label>
                <input type="text" name="penanganan" class="form-control" value="{{ $pelanggaran->penanganan }}">
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.pelanggaran.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
