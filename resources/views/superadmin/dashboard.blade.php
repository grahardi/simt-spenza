@extends('layouts.adminlte')

@section('title', 'Dashboard Superadmin')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stat['absensi_hari_ini'] }}</h3>
                <p>Absensi Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-clipboard-check"></i></div>
            <a href="{{ route('absensi.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ $stat['terlambat_hari_ini'] }}</h3>
                <p>Terlambat Hari Ini</p>
            </div>
            <div class="icon"><i class="fas fa-clock"></i></div>
            <a href="{{ route('absensi.telat.list') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stat['pelanggaran_bulan_ini'] }}</h3>
                <p>Pelanggaran Bulan Ini</p>
            </div>
            <div class="icon"><i class="fas fa-gavel"></i></div>
            <a href="{{ route('tatib.index') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-secondary">
            <div class="inner">
                <h3>{{ $stat['notifikasi_belum_diaksi'] }}</h3>
                <p>Notifikasi Belum Diaksi Guru</p>
            </div>
            <div class="icon"><i class="fas fa-bell"></i></div>
            <a href="{{ route('ajuan-guru.list') }}" class="small-box-footer">Detail <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Absensi Bulan Ini</h3></div>
            <div class="card-body">
                <p class="mb-0">Total <strong>{{ $stat['absensi_bulan_ini'] }}</strong> catatan absensi (Sakit/Ijin/Alfa/Dispensasi) bulan berjalan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Keterlambatan Bulan Ini</h3></div>
            <div class="card-body">
                <p class="mb-0">Total <strong>{{ $stat['terlambat_bulan_ini'] }}</strong> catatan keterlambatan bulan berjalan.</p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h3 class="card-title">Pelanggaran Belum Ditangani</h3></div>
            <div class="card-body">
                <p class="mb-0"><strong>{{ $stat['pelanggaran_belum_ditangani'] }}</strong> laporan pelanggaran masih menunggu poin/tindakan dari Tatib.</p>
            </div>
        </div>
    </div>
</div>
@endsection
