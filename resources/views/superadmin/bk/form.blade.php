@extends('layouts.adminlte')

@section('title', 'Edit Bimbingan')

@section('content')
<div class="card" style="max-width:480px;">
    <div class="card-header"><h3 class="card-title">Edit Bimbingan - {{ $bk->siswa->nama_lengkap ?? '' }}</h3></div>
    <div class="card-body">
        <form method="POST" action="{{ route('superadmin.bk.update', $bk) }}">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Tanggal</label>
                <input type="date" name="tgl_bimbingan" class="form-control" value="{{ $bk->tgl_bimbingan->format('Y-m-d') }}" required>
            </div>
            <div class="form-group">
                <label>Jenis</label>
                <select name="kategori" class="form-control" required>
                    @foreach (\App\Models\Bimbingan::KATEGORI as $k)
                        <option value="{{ $k }}" @selected($bk->kategori === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Keterangan</label>
                <textarea name="Keterangan" class="form-control" rows="3">{{ $bk->Keterangan }}</textarea>
            </div>
            <div class="form-group">
                <label>Tindakan</label>
                <select name="Tindakan" class="form-control" required>
                    @foreach (\App\Models\Bimbingan::TINDAKAN as $t)
                        <option value="{{ $t }}" @selected($bk->Tindakan === $t)>{{ $t }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('superadmin.bk.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
