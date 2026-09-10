@extends('layouts.app')

@section('title', 'List Upload Soal')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-th-list me-2"></i>List Upload Soal</h1>
</div>

<ul class="nav nav-tabs mb-3" role="tablist">
    @foreach (['7', '8', '9'] as $i => $kelas)
        <li class="nav-item">
            <button class="nav-link {{ $i === 0 ? 'active' : '' }}" data-bs-toggle="tab" data-bs-target="#tabKelas{{ $kelas }}" type="button">
                Kelas {{ $kelas }}
            </button>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach (['7', '8', '9'] as $i => $kelas)
        <div class="tab-pane fade {{ $i === 0 ? 'show active' : '' }}" id="tabKelas{{ $kelas }}">
            <div class="bg-white rounded shadow overflow-hidden">
                <div class="table-responsive">
                <table class="table table-striped mb-0 align-middle">
                    <thead>
                        <tr><th>Mata Pelajaran</th><th class="text-center">Format Aplikasi</th><th class="text-center">Format Cetak</th></tr>
                    </thead>
                    <tbody>
                        @foreach ($dataPerKelas[$kelas] as $baris)
                            <tr>
                                <td>{{ $baris['mapel'] }}</td>
                                <td class="text-center">
                                    @if ($baris['aplikasi'])
                                        <a href="{{ Storage::url($baris['aplikasi']->path) }}" target="_blank" class="badge bg-success text-decoration-none">
                                            <i class="fas fa-check me-1"></i> Sudah - Download
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($baris['cetak'])
                                        <a href="{{ Storage::url($baris['cetak']->path) }}" target="_blank" class="badge bg-success text-decoration-none">
                                            <i class="fas fa-check me-1"></i> Sudah - Download
                                        </a>
                                    @else
                                        <span class="badge bg-secondary">Belum</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
@endsection
