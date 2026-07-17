@extends('layouts.app')

@section('title', 'Data Absensi Siswa')

@php
    $bisaUbah = $tanggal->isToday() && auth('member')->user()->hasRole('piket');
@endphp

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-list fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Absensi Siswa</h1>
    </div>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label for="tgl" class="form-label mb-0"><i class="fas fa-calendar-alt me-1"></i> Tanggal</label>
        <input type="date" id="tgl" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

@if (!$absensi->isEmpty())
    <div class="row g-2 mb-3">
        <div class="col-3">
            <div class="bg-white rounded shadow-sm p-3 text-center">
                <div class="h5 mb-0" style="color:#854f0b">{{ $rekap['s'] ?? 0 }}</div>
                <small class="text-muted">Sakit</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-white rounded shadow-sm p-3 text-center">
                <div class="h5 mb-0" style="color:#3b6d11">{{ $rekap['i'] ?? 0 }}</div>
                <small class="text-muted">Ijin</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-white rounded shadow-sm p-3 text-center">
                <div class="h5 mb-0" style="color:#a32d2d">{{ $rekap['a'] ?? 0 }}</div>
                <small class="text-muted">Alfa</small>
            </div>
        </div>
        <div class="col-3">
            <div class="bg-white rounded shadow-sm p-3 text-center">
                <div class="h5 mb-0" style="color:#185fa5">{{ $rekap['d'] ?? 0 }}</div>
                <small class="text-muted">Dispensasi</small>
            </div>
        </div>
    </div>
@endif

<div class="p-4 bg-white rounded shadow">
    <h3 class="h6 mb-3 text-muted">
        <i class="fas fa-clone me-2"></i>
        {{ $tanggal->translatedFormat('l, d F Y') }}
    </h3>

    @if ($absensi->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i>
            Data Absensi <span class="text-primary fst-italic">{{ $tanggal->translatedFormat('d F Y') }}</span> tidak ada.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle absensi-table">
                <thead>
                    <tr>
                        <th>Siswa</th>
                        <th>Kelas</th>
                        <th>Status</th>
                        <th>Kemarin</th>
                        @if ($bisaUbah)
                            <th></th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $a)
                        <tr>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <span class="avatar-inisial">{{ strtoupper(substr($a->siswa->nama_lengkap, 0, 1)) }}</span>
                                    <span class="fw-semibold">{{ $a->siswa->nama_lengkap }}</span>
                                </div>
                            </td>
                            <td><span class="text-muted">{{ $a->siswa->kelas }}</span></td>
                            <td>
                                <span class="badge-status badge-{{ $a->keterangan }}">{{ $a->labelKeterangan() }}</span>
                                @if (in_array($a->keterangan, ['s', 'i']) && $a->gambar)
                                    <a href="{{ route('absensi.foto', $a) }}" target="_blank" class="btn btn-sm btn-outline-secondary ms-1 py-0 px-2">
                                        <i class="fas fa-image"></i>
                                    </a>
                                @elseif (in_array($a->keterangan, ['s', 'i']))
                                    <span class="text-muted small d-block">Tanpa foto</span>
                                @endif
                            </td>
                            <td>
                                @php $keb = $absenSebelumnya[$a->id_siswa] ?? null; @endphp
                                @if ($keb)
                                    <span class="badge-status badge-{{ $keb->keterangan }}">{{ $keb->labelKeterangan() }}</span>
                                @else
                                    <span class="text-muted small">Hadir</span>
                                @endif
                            </td>
                            @if ($bisaUbah)
                                <td class="text-end">
                                    <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#modalUbah{{ $a->id_siswa }}">
                                        <i class="fas fa-edit"></i> Ubah
                                    </button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $absensi->onEachSide(1)->links() }}
    @endif
</div>

{{-- Modal ditaruh di LUAR tabel - taruh di dalam tabel bikin browser "membetulkan"
     HTML yang tidak valid dan modal jadi berantakan/backdrop tidak muncul benar. --}}
@if ($bisaUbah)
    @foreach ($absensi as $a)
        <div class="modal fade" id="modalUbah{{ $a->id_siswa }}" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ubah Absensi - {{ $a->siswa->nama_lengkap }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form method="POST" action="{{ route('absensi.tandai', $a->siswa) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Keterangan</label>
                                <select name="keterangan" class="form-select" required>
                                    <option value="h" @selected($a->keterangan === 'h')>Hadir</option>
                                    <option value="s" @selected($a->keterangan === 's')>Sakit</option>
                                    <option value="i" @selected($a->keterangan === 'i')>Ijin</option>
                                    <option value="a" @selected($a->keterangan === 'a')>Alfa</option>
                                    <option value="d" @selected($a->keterangan === 'd')>Dispensasi</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto Bukti (opsional, ganti kalau perlu)</label>
                                <input type="file" name="foto" accept="image/*" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Keterangan Tambahan</label>
                                <input type="text" name="catatan" class="form-control" value="{{ $a->tambahan }}">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                    <form method="POST" action="{{ route('absensi.hapus', $a) }}"
                          onsubmit="return confirm('Yakin hapus absensi {{ $a->siswa->nama_lengkap }} hari ini?')"
                          class="px-3 pb-3">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="fas fa-trash me-1"></i> Hapus Absensi Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endforeach
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
