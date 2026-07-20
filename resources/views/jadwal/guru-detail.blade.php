@extends($layout ?? 'layouts.app')

@php $prefix = request()->routeIs('jadwal-publik.*') ? 'jadwal-publik.' : 'jadwal.'; @endphp

@section('title', 'Jadwal - ' . $guru->nama)

@php
    $hariIni = \App\Models\Member::namaHariJakartaHuruBesar();
    $palet = ['blue', 'teal', 'amber', 'coral', 'pink', 'green', 'purple'];
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-chalkboard-teacher fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Jadwal {{ $guru->nama }}</h1>
    </div>
    <a href="{{ route($prefix.'pilih-guru') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-arrow-left me-1"></i> Ganti Guru
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

@php
    $bolehTandai = auth('member')->user() && (
        auth('member')->user()->hasRole('piket')
        || auth('member')->user()->hasRole('admin')
        || auth('member')->user()->hasRole('kesiswaan')
    );
@endphp

@if ($prefix === 'jadwal.')
    <div class="px-4 py-3 mb-3 bg-white rounded shadow">
        <h6 class="mb-2"><i class="fas fa-user-check me-1"></i> Absensi Guru Hari Ini</h6>
        @if ($absenGuruHariIni)
            {{-- Status ini kelihatan untuk SEMUA role yang login (piket, kepsek, kurikulum, dll) --}}
            <span class="badge-status badge-{{ $absenGuruHariIni->status }}">
                {{ $absenGuruHariIni->labelStatus() }}
            </span>
            @if ($absenGuruHariIni->keterangan)
                <span class="text-muted small ms-2">{{ $absenGuruHariIni->keterangan }}</span>
            @endif
        @elseif ($bolehTandai)
            {{-- Tombol TANDAI cuma untuk piket/admin/kesiswaan --}}
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn-absen btn-absen-sakit" data-bs-toggle="modal" data-bs-target="#modalAbsenGuru-s">
                    <i class="fas fa-thermometer me-1"></i> Sakit
                </button>
                <button type="button" class="btn-absen btn-absen-ijin" data-bs-toggle="modal" data-bs-target="#modalAbsenGuru-i">
                    <i class="fas fa-envelope me-1"></i> Ijin
                </button>
                <button type="button" class="btn-absen btn-absen-dispensasi" data-bs-toggle="modal" data-bs-target="#modalAbsenGuru-d">
                    <i class="fas fa-bus me-1"></i> Dispensasi
                </button>
                <form method="POST" action="{{ route('jadwal.guru.absen', $guru) }}" class="d-inline">
                    @csrf
                    <input type="hidden" name="status" value="a">
                    <button type="submit" class="btn-absen btn-absen-alfa">
                        <i class="fas fa-times me-1"></i> Alfa
                    </button>
                </form>
            </div>
        @else
            <p class="text-muted small mb-0">Belum ada ajuan/absensi untuk hari ini.</p>
        @endif
    </div>

    @if ($bolehTandai && !$absenGuruHariIni)
        @foreach (['s' => 'Sakit', 'i' => 'Ijin', 'd' => 'Dispensasi'] as $kode => $label)
            <div class="modal fade" id="modalAbsenGuru-{{ $kode }}" tabindex="-1">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('jadwal.guru.absen', $guru) }}" class="modal-content">
                        @csrf
                        <input type="hidden" name="status" value="{{ $kode }}">
                        <div class="modal-header">
                            <h5 class="modal-title">Absen {{ $label }} - {{ $guru->nama }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label">Keterangan (opsional)</label>
                            <input type="text" name="keterangan" class="form-control" placeholder="contoh: demam, ada urusan keluarga">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    @endif
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($jadwalPerHari->isEmpty() || ($prefix === 'jadwal.' && !isset($jadwalPerHari[$hariIni])))
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i>
            {{ $prefix === 'jadwal.' ? 'Tidak ada jadwal mengajar untuk '.$guru->nama.' hari ini.' : 'Belum ada jadwal mengajar untuk '.$guru->nama.'.' }}
        </div>
    @else
        @foreach ($urutanHari as $hari)
            @continue(!isset($jadwalPerHari[$hari]))
            @continue($prefix === 'jadwal.' && $hari !== $hariIni)
            <h3 class="h6 text-uppercase text-muted mt-4 mb-2">
                {{ $hari }}
                @if ($hari === $hariIni) <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Hari ini</span> @endif
            </h3>

            <div class="d-flex flex-column gap-2 mb-2">
                @php $warnaIndex = -1; $kunciSebelumnya = null; @endphp
                @foreach ($jadwalPerHari[$hari]->sortBy('jamhari') as $j)
                    @php
                        $kunciSekarang = $j->kelas.'|'.$j->mapel;
                        $blokBaru = $kunciSekarang !== $kunciSebelumnya;
                        if ($blokBaru) {
                            $warnaIndex++;
                        }
                        $kunciSebelumnya = $kunciSekarang;
                        $warna = $palet[$warnaIndex % count($palet)];

                        // Tombol Upload/Lihat Tugas: HANYA di baris PERTAMA tiap blok
                        // kelas+mapel (jam berurutan gabung jadi 1 tombol saja), dan
                        // HANYA kalau guru sudah tercatat absen hari ini & bukan Alfa.
                        $tampilkanTombolTugas = $blokBaru && $hari === $hariIni && $prefix === 'jadwal.'
                            && $absenGuruHariIni && $absenGuruHariIni->status !== 'a';
                        $tugasSudahAda = $tampilkanTombolTugas ? ($tugasHariIni[$j->kelas] ?? null) : null;
                    @endphp
                    <div class="jadwal-baris bg-{{ $warna }}">
                        <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                        <span class="jadwal-waktu-kecil">{{ $j->waktu ?? '-' }}</span>
                        <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                        <span class="jadwal-mapel-kecil">{{ $j->mapelLengkap() }}</span>
                        @if ($tampilkanTombolTugas)
                            <a href="{{ route('tugas.upload', [$guru, $j->kelas]) }}" class="btn btn-sm btn-outline-dark" style="border-color:currentColor;color:inherit;">
                                @if ($tugasSudahAda)
                                    <i class="fas fa-eye me-1"></i> Lihat Tugas
                                @else
                                    <i class="fas fa-clipboard-list me-1"></i> Upload Tugas
                                @endif
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>
        @endforeach
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
