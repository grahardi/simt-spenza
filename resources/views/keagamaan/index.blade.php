@extends('layouts.app')

@section('title', 'Laporan Keagamaan')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-pray fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Laporan Kegiatan Keagamaan Hari Ini</h1>
    </div>
    <a href="{{ route('keagamaan.rekap') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-list me-1"></i> Rekap Semua
    </a>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="p-4 bg-white rounded shadow">
    @if ($siswa->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i>
            Tidak ada jadwal jam sholat (jamhari 'x') untuk akun ini hari ini,
            atau kelas {{ $kelasList->implode(', ') ?: '-' }} belum punya siswa.
        </div>
    @else
        @foreach ($siswa as $s)
            <div class="siswa-row-ringkas {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="siswa-nama">
                    <span class="text-primary">{{ $s->id_member }}</span> - {{ $s->nama_lengkap }}
                    <div class="text-muted" style="font-size:11px">Kelas {{ $s->kelas }}</div>
                </div>

                @if ($s->absenHariIni)
                    <span class="badge-status badge-{{ $s->absenHariIni->keterangan }}">{{ $s->absenHariIni->labelKeterangan() }}</span>
                @elseif ($s->laporHariIni)
                    <span class="badge-status" style="background:#eeedfe;color:#534ab7;">{{ $s->laporHariIni->label() }}</span>
                @else
                    <div class="siswa-aksi">
                        <form method="POST" action="{{ route('keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="pelanggaran" value="halangan">
                            <button type="submit" class="btn-absen" style="background:#faeeda;color:#854f0b;">
                                <i class="fas fa-venus me-1"></i> Halangan
                            </button>
                        </form>
                        <form method="POST" action="{{ route('keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="pelanggaran" value="membolos">
                            <button type="submit" class="btn-absen" style="background:#fcebeb;color:#a32d2d;">
                                <i class="fas fa-ban me-1"></i> Bolos
                            </button>
                        </form>
                        <form method="POST" action="{{ route('keagamaan.simpan', $s) }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="pelanggaran" value="ijin">
                            <button type="submit" class="btn-absen" style="background:#eaf3de;color:#3b6d11;">
                                <i class="fas fa-check-circle me-1"></i> Ijin
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        @endforeach
    @endif
</div>
@endsection
