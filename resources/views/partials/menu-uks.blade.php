@php $rute = request()->route()->getName(); @endphp

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-home me-1"></i> Home
    </a>
    <a href="{{ route('uks.cari') }}" class="btn btn-sm {{ $rute === 'uks.cari' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-briefcase-medical me-1"></i> Siswa Sakit
    </a>
    <a href="{{ route('uks.list') }}" class="btn btn-sm {{ $rute === 'uks.list' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-bed me-1"></i> Siswa di UKS
    </a>
    <a href="{{ route('uks.panggilan') }}" class="btn btn-sm {{ $rute === 'uks.panggilan' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-phone-alt me-1"></i> Panggilan Wali Murid
    </a>
</div>
