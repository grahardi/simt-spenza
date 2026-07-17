@extends('layouts.app')

@section('title', 'Profil - ' . $siswa->nama_lengkap)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-user-graduate me-2"></i>Detail Data Siswa</h1>
</div>

<div class="p-4 bg-white rounded shadow mb-3">
    <div class="row">
        <div class="col-lg-3 text-center mb-3 mb-lg-0">
            @if ($siswa->foto_url)
                <img src="{{ $siswa->foto_url }}" alt="Foto Profil" class="rounded-circle shadow" style="width:140px;height:140px;object-fit:cover;">
            @else
                <span class="foto-siswa-placeholder mx-auto" style="width:140px;height:140px;font-size:36px;">{{ $siswa->initials() }}</span>
            @endif
        </div>
        <div class="col-lg-9">
            <div class="table-responsive">
                <table class="table table-striped">
                    <tr><td width="150">Nomor Induk</td><td width="10">:</td><td>{{ $siswa->id_member }}</td></tr>
                    <tr><td>NISN</td><td>:</td><td>{{ $siswa->nisn }}</td></tr>
                    <tr><td>Kelas</td><td>:</td><td>{{ $siswa->kelas }}</td></tr>
                    <tr><td>Nama Lengkap</td><td>:</td><td>{{ $siswa->nama_lengkap }}</td></tr>
                    <tr><td>Jenis Kelamin</td><td>:</td><td>{{ $siswa->jenis_kelamin }}</td></tr>
                    <tr><td>Alamat</td><td>:</td><td>{{ $siswa->alamat }}</td></tr>
                    <tr><td>TTL</td><td>:</td><td>{{ $siswa->email }}</td></tr>
                    <tr><td>WhatsApp</td><td>:</td><td>{{ $siswa->whatsapp }}</td></tr>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="p-4 bg-white rounded shadow mb-3">
    <h3 class="h6 mb-3"><i class="fas fa-clone me-2"></i>Data Absensi</h3>
    @if ($absensi->isEmpty())
        <div class="text-muted small">Belum ada riwayat absensi.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>No</th><th>Tanggal</th><th>Status</th><th>Keterangan</th></tr></thead>
                <tbody>
                    @foreach ($absensi as $i => $a)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td>{{ $a->tgl_absen->translatedFormat('d F Y') }}</td>
                            <td><span class="badge-status badge-{{ $a->keterangan }}">{{ $a->labelKeterangan() }}</span></td>
                            <td>{{ $a->tambahan }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="p-4 bg-white rounded shadow mb-3">
    <h3 class="h6 mb-3"><i class="fas fa-clock me-2"></i>Data Keterlambatan</h3>
    @if ($keterlambatan->isEmpty())
        <div class="text-muted small">Belum ada riwayat terlambat.</div>
    @else
        <div class="table-responsive">
            <table class="table table-striped">
                <thead><tr><th>No</th><th>Tanggal Terlambat</th></tr></thead>
                <tbody>
                    @foreach ($keterlambatan as $i => $t)
                        <tr><td>{{ $i + 1 }}</td><td>{{ $t->tgl_absen->translatedFormat('d F Y') }}</td></tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h6 mb-3"><i class="fas fa-gavel me-2"></i>Data Pelanggaran</h3>
    @if ($pelanggaran->isEmpty())
        <div class="text-muted small">Belum ada riwayat pelanggaran.</div>
    @else
        <div class="d-flex flex-wrap gap-2 mb-3" id="tabTahunPelanggaran">
            @foreach ($pelanggaran->keys() as $i => $tahun)
                <button type="button"
                        class="btn btn-sm tab-tahun {{ $i === 0 ? 'active' : '' }}"
                        data-target="pelanggaran-{{ \Illuminate\Support\Str::slug($tahun) }}"
                        onclick="gantiTabTahun(this)">
                    {{ $tahun }}
                </button>
            @endforeach
        </div>

        @foreach ($pelanggaran as $tahun => $daftar)
            <div class="tab-panel-pelanggaran" id="pelanggaran-{{ \Illuminate\Support\Str::slug($tahun) }}" style="{{ $loop->first ? '' : 'display:none;' }}">
                @if ($daftar->isEmpty())
                    <div class="text-muted small py-3">Tidak ada pelanggaran di tahun ajaran {{ $tahun }}.</div>
                @else
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead><tr><th>No</th><th>Tanggal</th><th>Jenis</th><th>Keterangan</th><th>Poin</th><th>Penanganan</th></tr></thead>
                        <tbody>
                            @foreach ($daftar as $i => $p)
                                @php $belumDitangani = $p->penanganan === null || strtolower($p->penanganan) === 'belum'; @endphp
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $p->tgl_pelanggaran->translatedFormat('d F Y') }}</td>
                                    <td>{{ $p->kategori }}</td>
                                    <td>{{ $p->keterangan }}</td>
                                    <td>{{ $belumDitangani ? '-' : $p->poin }}</td>
                                    <td>
                                        @if ($belumDitangani)
                                            <span class="badge-status" style="background:#fcebeb;color:#a32d2d;">Belum ditangani</span>
                                        @else
                                            {{ $p->penanganan }}
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="fw-bold">
                                <td colspan="4" class="text-end">Total Poin {{ $tahun }} <span class="fw-normal text-muted">(hanya yang sudah ditangani)</span></td>
                                <td colspan="2">{{ $daftar->sum(fn ($p) => is_numeric($p->poin) ? (int) $p->poin : 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @endif
            </div>
        @endforeach
    @endif
</div>

<script>
function gantiTabTahun(btn) {
    document.querySelectorAll('#tabTahunPelanggaran .tab-tahun').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');
    document.querySelectorAll('.tab-panel-pelanggaran').forEach(el => el.style.display = 'none');
    document.getElementById(btn.dataset.target).style.display = '';
}
</script>
@endsection
