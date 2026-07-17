@extends('layouts.adminlte')

@section('title', 'Mutasi Siswa')

@section('content')
<div class="card" style="max-width:500px;">
    <div class="card-header"><h3 class="card-title">Mutasi - {{ $siswa->nama_lengkap }}</h3></div>
    <div class="card-body">
        <p class="text-muted">Kelas saat ini: <strong>{{ $siswa->kelas }}</strong></p>
        <form method="POST" action="{{ route('superadmin.siswa.mutasi', $siswa) }}">
            @csrf
            <div class="form-group">
                <label>Kelas Baru</label>
                <select name="kelas_baru" class="form-control" required>
                    @foreach ($daftarKelas as $k)
                        <option value="{{ $k }}">{{ $k }}</option>
                    @endforeach
                    <option value="OUT">OUT (Keluar/Lulus)</option>
                </select>
            </div>
            <div class="form-group">
                <label>Alasan Mutasi (opsional, untuk catatan)</label>
                <textarea name="alasan" class="form-control" rows="2" placeholder="contoh: pindah sekolah, naik kelas, lulus"></textarea>
            </div>
            <button type="submit" class="btn btn-warning"><i class="fas fa-exchange-alt me-1"></i> Proses Mutasi</button>
            <a href="{{ route('superadmin.siswa.index') }}" class="btn btn-outline-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
