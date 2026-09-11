@extends('layouts.app')

@section('title', 'Kelola Soal')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-file-alt fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">{{ $semua ? 'Kelola Soal' : 'Soal yang Saya Upload' }}</h1>
    </div>
    @if ($semua && $daftar->isNotEmpty())
        <a href="{{ route('soal-upload.download-semua') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
            <i class="fas fa-download me-1"></i> Download Semua (ZIP)
        </a>
    @endif
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if (session('status_gagal'))
    <div class="alert alert-danger">{{ session('status_gagal') }}</div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada soal yang terupload.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr>
                    <th>Kelas</th><th>Mapel</th><th>Tipe</th>
                    @if ($semua)<th>Guru</th>@endif
                    <th>Terakhir Diupload</th><th class="text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($daftar as $s)
                    <tr>
                        <td>{{ $s->kelas }}</td>
                        <td>{{ $s->mapel }}</td>
                        <td><span class="badge {{ $s->tipe === 'cetak' ? 'bg-warning text-dark' : 'bg-info text-dark' }}">{{ \App\Models\SoalUpload::LABEL_TIPE[$s->tipe] }}</span></td>
                        @if ($semua)<td>{{ $s->guru->nama ?? '-' }}</td>@endif
                        <td>{{ $s->updated_at->translatedFormat('d M Y, H:i') }}</td>
                        <td class="text-end">
                            <a href="{{ Storage::url($s->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                            <form method="POST" action="{{ route('soal-upload.hapus', $s) }}" class="d-inline" onsubmit="return confirm('Hapus soal ini? File akan dipindah ke arsip (tidak benar-benar hilang).')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

@if ($semua)
    <div class="p-4 bg-white rounded shadow mt-3">
        <h6 class="mb-3"><i class="fas fa-paperclip me-1"></i> Berkas Lain</h6>
        <p class="text-muted small">Upload bebas tanpa kategori kelas/mapel - untuk berkas admin atau lainnya, keterangan diisi manual. Hanya Admin Soal yang bisa lihat/upload bagian ini.</p>

        <form method="POST" action="{{ route('soal-upload.simpan-berkas-lain') }}" enctype="multipart/form-data" class="d-flex flex-wrap gap-2 mb-3">
            @csrf
            <input type="text" name="keterangan" class="form-control" placeholder="Keterangan, contoh: Berkas Admin" style="max-width:260px" required>
            <input type="file" name="file_berkas" class="form-control" style="max-width:260px" required>
            <button type="submit" class="btn btn-outline-primary"><i class="fas fa-upload me-1"></i> Upload</button>
        </form>

        @if ($daftarBerkasLain->isEmpty())
            <p class="text-muted small mb-0">Belum ada berkas lain yang diupload.</p>
        @else
            <ul class="list-group">
                @foreach ($daftarBerkasLain as $b)
                    <li class="list-group-item d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <div>
                            <strong>{{ $b->keterangan }}</strong>
                            <span class="text-muted small d-block">{{ $b->nama_file_asli }} &middot; {{ $b->created_at->translatedFormat('d M Y, H:i') }}</span>
                        </div>
                        <div>
                            <a href="{{ Storage::url($b->path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-download me-1"></i> Download
                            </a>
                            <form method="POST" action="{{ route('soal-upload.hapus-berkas-lain', $b) }}" class="d-inline" onsubmit="return confirm('Hapus berkas ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@endif
@endsection
