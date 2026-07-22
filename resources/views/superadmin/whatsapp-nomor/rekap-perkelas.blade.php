@extends('layouts.adminlte')

@section('title', 'Rekap Perkelas Registrasi WA')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Rekap Perkelas - Registrasi WhatsApp Wali Murid</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.whatsapp-nomor.index') }}" class="btn btn-sm btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Kembali
            </a>
        </div>
    </div>
    <div class="card-body">
        <p class="text-muted small">1 siswa dihitung 1x sebagai "sudah registrasi" meski nomor Ayah dan Ibu keduanya terdaftar.</p>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kelas</th>
                    <th class="text-center">Total Siswa</th>
                    <th class="text-center">Sudah Registrasi</th>
                    <th class="text-center">Belum Registrasi</th>
                    <th class="text-center">%  Registrasi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($rekap as $r)
                    <tr>
                        <td>{{ $r->kelas }}</td>
                        <td class="text-center">{{ $r->total_siswa }}</td>
                        <td class="text-center text-success fw-bold">{{ $r->sudah_daftar }}</td>
                        <td class="text-center text-danger fw-bold">{{ $r->belum_daftar }}</td>
                        <td class="text-center">{{ $r->total_siswa > 0 ? round($r->sudah_daftar / $r->total_siswa * 100) : 0 }}%</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-center text-muted">Belum ada data siswa.</td></tr>
                @endforelse
            </tbody>
            @if ($rekap->isNotEmpty())
                <tfoot>
                    <tr class="fw-bold">
                        <th>Total Keseluruhan</th>
                        <th class="text-center">{{ $totalKeseluruhan->total_siswa }}</th>
                        <th class="text-center text-success">{{ $totalKeseluruhan->sudah_daftar }}</th>
                        <th class="text-center text-danger">{{ $totalKeseluruhan->belum_daftar }}</th>
                        <th class="text-center">{{ $totalKeseluruhan->total_siswa > 0 ? round($totalKeseluruhan->sudah_daftar / $totalKeseluruhan->total_siswa * 100) : 0 }}%</th>
                    </tr>
                </tfoot>
            @endif
        </table>
        </div>
    </div>
</div>
@endsection
