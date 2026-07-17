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

{{-- Nav tab tahun ajaran (arsip per tahun, aplikasi mulai 2025/2026) --}}
<ul class="nav nav-pills mb-3 gap-2">
    @foreach ($daftarTahunAjaran as $th)
        <li class="nav-item">
            <a href="{{ route('tatib.index', ['tahun' => $th]) }}"
               class="nav-link {{ $th === $tahunAjaran ? 'active' : '' }}"
               style="{{ $th === $tahunAjaran ? 'background:#4b0082;' : 'background:#f0f0f0;color:#555;' }}">
                {{ $th }}/{{ $th + 1 }}
            </a>
        </li>
    @endforeach
</ul>

<div class="p-4 bg-white rounded shadow">
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
        <h3 class="h6 text-muted mb-0">Tahun Ajaran {{ $tahunAjaran }}/{{ $tahunAjaran + 1 }}</h3>
        <div class="d-flex gap-2" id="tabSubPelanggaran">
            <button type="button" class="tab-tahun active" data-target="panelDaftarPelanggaran" onclick="gantiSubTab(this)">
                Data Pelanggaran
            </button>
            <button type="button" class="tab-tahun" data-target="panelAkumulasiPoin" onclick="gantiSubTab(this)">
                Akumulasi Poin
            </button>
        </div>
    </div>

    <div class="sub-tab-panel" id="panelDaftarPelanggaran">
        @if ($pelanggaran->isEmpty())
            <div class="text-muted text-center py-4">
                <i class="far fa-question-circle me-1"></i> Tidak ada laporan pelanggaran di tahun ajaran ini.
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
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
                        @foreach ($pelanggaran as $p)
                            @php $belumDitangani = !$p->sudahDitangani(); @endphp
                            <tr>
                                <td class="small">{{ $p->tgl_pelanggaran->translatedFormat('d M Y') }}</td>
                                <td>{{ $p->siswa->nama_lengkap ?? '-' }}</td>
                                <td>
                                    @php
                                        $warnaKat = ['Peringatan' => 'badge-d', 'Ringan' => 'badge-i', 'Sedang' => 'badge-s', 'Berat' => 'badge-a'][$p->kategori] ?? '';
                                    @endphp
                                    <span class="badge-status {{ $warnaKat }}">{{ $p->kategori }}</span>
                                </td>
                                <td>{{ $belumDitangani ? '-' : $p->poin }}</td>
                                <td class="small">{{ $p->keterangan }}</td>
                                <td>{{ $p->pelapor->nama ?? '-' }}</td>
                                <td>
                                    @if ($belumDitangani)
                                        <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Belum ditangani</span>
                                    @else
                                        <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">{{ $p->penanganan }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    @if ($belumDitangani)
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

    <div class="sub-tab-panel" id="panelAkumulasiPoin" style="display:none;">
        @if ($akumulasiPoin->isEmpty())
            <div class="text-muted small">Belum ada data.</div>
        @else
            <div class="table-responsive">
                <table class="table table-sm table-striped">
                    <thead><tr><th>No</th><th>Siswa</th><th>Kelas</th><th>Jumlah Kejadian</th><th>Total Poin</th></tr></thead>
                    <tbody>
                        @foreach ($akumulasiPoin as $a)
                            <tr>
                                <td>{{ $akumulasiPoin->firstItem() + $loop->index }}</td>
                                <td>{{ $a->siswa->nama_lengkap ?? '-' }}</td>
                                <td>{{ $a->siswa->kelas ?? '-' }}</td>
                                <td>{{ $a->jumlah_kejadian }}</td>
                                <td class="fw-bold">{{ $a->total_poin }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $akumulasiPoin->onEachSide(1)->links() }}
        @endif
    </div>
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

<script>
function gantiSubTab(btn) {
    document.querySelectorAll('#tabSubPelanggaran .tab-tahun').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.sub-tab-panel').forEach(el => el.style.display = 'none');
    document.getElementById(btn.dataset.target).style.display = '';
}
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
