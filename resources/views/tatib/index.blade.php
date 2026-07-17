@extends('layouts.app')

@section('title', 'Data Pelanggaran')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-gavel fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Pelanggaran Siswa</h1>
    </div>
    <a href="{{ route('tatib.cari') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-plus me-1"></i> Lapor Pelanggaran
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    @if ($pelanggaran->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Tidak ada laporan pelanggaran pada {{ $tanggal->translatedFormat('d F Y') }}.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Siswa</th>
                        <th>Kategori</th>
                        <th>Poin</th>
                        <th>Keterangan</th>
                        <th>Pelapor</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pelanggaran as $i => $p)
                        <tr>
                            <td>{{ $pelanggaran->firstItem() + $i }}</td>
                            <td>{{ $p->siswa->nama_lengkap ?? '-' }}</td>
                            <td>
                                @php
                                    $warnaKat = ['Peringatan' => 'badge-d', 'Ringan' => 'badge-i', 'Sedang' => 'badge-s', 'Berat' => 'badge-a'][$p->kategori] ?? '';
                                @endphp
                                <span class="badge-status {{ $warnaKat }}">{{ $p->kategori }}</span>
                            </td>
                            <td>{{ $p->poin }}</td>
                            <td class="small">{{ $p->keterangan }}</td>
                            <td>{{ $p->pelapor->nama ?? '-' }}</td>
                            <td>
                                @if ($p->sudahDitangani())
                                    <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">{{ $p->penanganan }}</span>
                                @else
                                    <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Belum</span>
                                @endif
                            </td>
                            <td class="text-end">
                                @if (!$p->sudahDitangani())
                                    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#modalTindak{{ $p->id_langgar }}">
                                        <i class="fas fa-check me-1"></i> Tindak
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $pelanggaran->onEachSide(1)->links() }}
    @endif
</div>

@foreach ($pelanggaran as $p)
    @if (!$p->sudahDitangani())
        <div class="modal fade" id="modalTindak{{ $p->id_langgar }}" tabindex="-1">
            <div class="modal-dialog">
                <form method="POST" action="{{ route('tatib.tindak', $p) }}" class="modal-content">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Tindak Lanjut - {{ $p->siswa->nama_lengkap ?? '' }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <label class="form-label">Tindakan yang diambil</label>
                        <input type="text" name="penanganan" class="form-control" placeholder="contoh: Panggil orang tua, Surat peringatan" required>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
