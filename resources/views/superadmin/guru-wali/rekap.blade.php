@extends('layouts.adminlte')

@section('title', 'Rekap Jumlah Guru Wali')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Jumlah Siswa per Guru Wali</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.guru-wali.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
            <a href="{{ route('superadmin.guru-wali.export') }}" class="btn btn-sm btn-success">
                <i class="fas fa-file-excel me-1"></i> Export Excel
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr><th style="width:60px">No</th><th>Nama Guru Wali</th><th style="width:120px">Jumlah Siswa</th></tr>
            </thead>
            <tbody>
                @forelse ($rekapJumlah as $i => $r)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>
                            <a href="{{ route('superadmin.guru-wali.index', ['id_guru_wali' => $r->id_guru_wali]) }}">
                                {{ $r->guru->nama ?? '-' }}
                            </a>
                        </td>
                        <td><span class="badge badge-primary">{{ $r->jumlah }}</span></td>
                    </tr>
                @empty
                    <tr><td colspan="3" class="text-center text-muted">Belum ada siswa yang di-assign ke guru wali manapun.</td></tr>
                @endforelse
            </tbody>
            @if ($rekapJumlah->isNotEmpty())
                <tfoot>
                    <tr>
                        <th colspan="2" class="text-end">Total</th>
                        <th>{{ $rekapJumlah->sum('jumlah') }}</th>
                    </tr>
                </tfoot>
            @endif
        </table>
        </div>
    </div>
</div>
@endsection
