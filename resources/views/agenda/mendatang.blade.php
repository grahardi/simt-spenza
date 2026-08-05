@extends('layouts.app')

@section('title', 'Agenda Mendatang')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-arrow-right fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Agenda Mendatang</h1>
    </div>
    <a href="{{ route('agenda.index') }}" class="btn btn-outline-light btn-sm mt-2 mt-md-0">
        <i class="fas fa-calendar me-1"></i> Lihat Kalender
    </a>
</div>

<div class="bg-white rounded shadow overflow-hidden">
    @if ($agenda->isEmpty())
        <div class="text-muted text-center py-4">
            <i class="far fa-question-circle me-1"></i> Belum ada agenda kegiatan mendatang.
        </div>
    @else
        <div class="table-responsive">
        <table class="table table-striped mb-0 align-middle">
            <thead>
                <tr><th>Tanggal</th><th>Judul</th><th>Penanggung Jawab</th><th>Kategori</th><th>Keterangan</th></tr>
            </thead>
            <tbody>
                @foreach ($agenda as $a)
                    <tr>
                        <td class="text-nowrap">
                            {{ $a->tanggal_mulai->translatedFormat('d M Y') }}
                            @if ($a->tanggal_selesai && !$a->tanggal_selesai->isSameDay($a->tanggal_mulai))
                                s/d {{ $a->tanggal_selesai->translatedFormat('d M Y') }}
                            @endif
                        </td>
                        <td>{{ $a->judul }}</td>
                        <td>@if ($a->ketua())Ketua: {{ $a->ketua()->guru->nama ?? '-' }}<br>@endif@if ($a->sekretaris())Sekretaris: {{ $a->sekretaris()->guru->nama ?? '-' }}@endif@if (!$a->ketua() && !$a->sekretaris())-@endif</td>
                        <td>
                            <span class="badge" style="background:{{ \App\Models\Agenda::KATEGORI_WARNA[$a->kategori] }};">
                                {{ \App\Models\Agenda::KATEGORI_LABEL[$a->kategori] }}
                            </span>
                        </td>
                        <td class="text-muted small">{{ $a->keterangan ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>

<div class="mt-3">
    {{ $agenda->onEachSide(1)->links() }}
</div>
@endsection
