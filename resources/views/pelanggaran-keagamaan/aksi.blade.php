@extends('layouts.app')

@section('title', 'Aksi Pelanggaran - Keagamaan')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-gavel me-2"></i>Aksi Pelanggaran - Kabur Sholat</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-1"></i> Menampilkan siswa dengan catatan <strong>Kabur minimal 3 kali</strong>.
    Tombol Aksi muncul tiap kelipatan 3 (3, 6, 9, dst) - setelah ditindak, tombol hilang sampai kelipatan berikutnya.
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada siswa dengan Kabur 3 kali atau lebih.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Nama</th><th>Kelas</th><th class="text-center">Jumlah Kabur</th><th class="text-end">Aksi</th></tr>
            </thead>
            <tbody>
                @foreach ($daftar as $d)
                    <tr>
                        <td>{{ $d->siswa->nama_lengkap ?? '-' }}</td>
                        <td>{{ $d->kelas }}</td>
                        <td class="text-center"><span class="badge bg-danger">{{ $d->jumlah_kabur }}</span></td>
                        <td class="text-end">
                            @if ($d->perluTindakan)
                                <button type="button" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#modalTindak{{ $d->id_siswa }}">
                                    <i class="fas fa-exclamation-triangle me-1"></i> Aksi
                                </button>
                            @else
                                <span class="badge bg-success">Sudah Ditindak</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

@foreach ($daftar as $d)
    @if ($d->perluTindakan)
        <div class="modal fade" id="modalTindak{{ $d->id_siswa }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('pelanggaran-keagamaan.simpan-tindakan', $d->id_siswa) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tindakan - {{ $d->siswa->nama_lengkap ?? '-' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p class="text-muted small">Sudah tercatat Kabur <strong>{{ $d->jumlah_kabur }} kali</strong>.</p>
                        <label class="form-label">Keterangan Tindakan</label>
                        <textarea name="keterangan" class="form-control" rows="3" placeholder="contoh: Sudah diberi peringatan lisan, orang tua akan dihubungi jika terulang." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
