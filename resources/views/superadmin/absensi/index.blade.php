@extends('layouts.adminlte')

@section('title', 'Data Absensi')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Data Absensi (semua tanggal)</h3></div>
    <div class="card-body">
        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-3">
                <input type="date" name="tgl" class="form-control" value="{{ request('tgl') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="kelas" class="form-control" placeholder="Kelas, mis. 7 - A" value="{{ request('kelas') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Tanggal</th><th>Siswa</th><th>Kelas</th><th>Status</th><th>Keterangan</th><th style="width:100px">Aksi</th></tr></thead>
            <tbody>
                @forelse ($absensi as $a)
                    <tr role="button" data-bs-toggle="modal" data-bs-target="#modalAbsen{{ $a->id_absen_siswa }}" style="cursor:pointer;">
                        <td>{{ $a->tgl_absen->translatedFormat('d M Y') }}</td>
                        <td>{{ $a->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $a->siswa->kelas ?? '-' }}</td>
                        <td>{{ $a->labelKeterangan() }}</td>
                        <td>{{ $a->tambahan }} @if ($a->gambar)<i class="fas fa-image text-muted ms-1" title="Ada foto surat"></i>@endif</td>
                        <td onclick="event.stopPropagation();">
                            <form method="POST" action="{{ route('superadmin.absensi.destroy', $a) }}" class="d-inline" onsubmit="return confirm('Yakin hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-xs btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Tidak ada data.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $absensi->onEachSide(1)->links() }}
    </div>
</div>

{{-- Modal HARUS di luar <table>/<tbody> - taruh di dalamnya bikin HTML tidak valid dan modal gagal berfungsi --}}
@foreach ($absensi as $a)
    <div class="modal fade" id="modalAbsen{{ $a->id_absen_siswa }}" tabindex="-1">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('superadmin.absensi.update', $a) }}" class="modal-content">
                @csrf @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">{{ $a->siswa->nama_lengkap ?? '-' }} - {{ $a->tgl_absen->translatedFormat('d F Y') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($a->gambar)
                        <label class="d-block mb-1">Foto Surat</label>
                        <a href="{{ Storage::url($a->gambar) }}" target="_blank">
                            <img src="{{ Storage::url($a->gambar) }}" class="img-fluid rounded border mb-3" style="max-height:280px;">
                        </a>
                    @else
                        <p class="text-muted small">Tidak ada foto surat untuk catatan ini.</p>
                    @endif

                    <input type="hidden" name="tgl_absen" value="{{ $a->tgl_absen->format('Y-m-d') }}">

                    <div class="form-group">
                        <label>Status</label>
                        <select name="keterangan" class="form-control" required>
                            <option value="h" @selected($a->keterangan === 'h')>Hadir</option>
                            <option value="s" @selected($a->keterangan === 's')>Sakit</option>
                            <option value="i" @selected($a->keterangan === 'i')>Ijin</option>
                            <option value="a" @selected($a->keterangan === 'a')>Alfa</option>
                            <option value="d" @selected($a->keterangan === 'd')>Dispensasi</option>
                        </select>
                    </div>
                    <div class="form-group mb-0">
                        <label>Keterangan Tambahan</label>
                        <input type="text" name="tambahan" class="form-control" value="{{ $a->tambahan }}">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
