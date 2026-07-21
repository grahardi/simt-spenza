@php $rute = request()->route()->getName(); @endphp

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-home me-1"></i> Home
    </a>
    <a href="{{ route('absensi.index') }}" class="btn btn-sm {{ $rute === 'absensi.index' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-clipboard-check me-1"></i> Absensi Hari Ini
    </a>
    <a href="{{ route('absensi.telat.list') }}" class="btn btn-sm {{ $rute === 'absensi.telat.list' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-clock me-1"></i> Keterlambatan
    </a>
    <a href="{{ route('kesiswaan.tidak-masuk') }}" class="btn btn-sm {{ $rute === 'kesiswaan.tidak-masuk' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-user-clock me-1"></i> Tidak Masuk 3+ Hari
    </a>
    <a href="{{ route('tatib.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'tatib.') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-gavel me-1"></i> Pelanggaran
    </a>
    <a href="{{ route('kesiswaan.rekap-mingguan') }}" class="btn btn-sm {{ $rute === 'kesiswaan.rekap-mingguan' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-calendar-week me-1"></i> Rekap Absen Mingguan
    </a>
</div>
