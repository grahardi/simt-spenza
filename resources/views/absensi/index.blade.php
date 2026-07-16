@extends('layouts.app')

@section('title', 'Data Absensi Siswa')

@section('content')
<div class="d-flex flex-column flex-md-row px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-list fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Data Absensi Siswa</h1>
    </div>
</div>

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label for="tgl" class="form-label mb-0">Tanggal</label>
        <input type="date" id="tgl" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $tanggal->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h5 mb-3">
        <i class="fas fa-clone me-2"></i>
        Data Absensi {{ $tanggal->translatedFormat('d F Y') }}
    </h3>

    @if ($absensi->isEmpty())
        <div class="text-muted">
            <i class="far fa-question-circle me-1"></i>
            Data Absensi <span class="text-primary fst-italic">{{ $tanggal->translatedFormat('d F Y') }}</span> tidak ada.
        </div>
    @else
        <div class="table-responsive">
            <table class="table table-striped align-middle">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Kelas</th>
                        <th>Absensi</th>
                        <th>Absensi Sebelumnya</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($absensi as $i => $a)
                        <tr>
                            <td>{{ $absensi->firstItem() + $i }}</td>
                            <td>{{ $a->siswa->nama_lengkap }}</td>
                            <td>{{ $a->siswa->kelas }}</td>
                            <td>
                                @if (in_array($a->keterangan, ['s', 'i']) && $a->gambar)
                                    <a href="{{ route('absensi.foto', $a) }}"
                                       class="btn btn-sm {{ $a->keterangan === 's' ? 'btn-warning' : 'btn-success' }}">
                                        {{ $a->labelKeterangan() }}
                                    </a>
                                @else
                                    <strong>{{ $a->labelKeterangan() }}</strong>
                                    @if (in_array($a->keterangan, ['s', 'i']))
                                        <span class="text-muted small"> - Tanpa Foto</span>
                                    @endif
                                @endif
                            </td>
                            <td>
                                {{ ($absenSebelumnya[$a->id_siswa] ?? null)?->labelKeterangan() ?? '-' }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{ $absensi->onEachSide(1)->links() }}
    @endif
</div>
@endsection
