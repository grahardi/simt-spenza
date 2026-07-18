@php $rute = request()->route()->getName(); @endphp

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('surat-masuk.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'surat-masuk') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-inbox me-1"></i> Surat Masuk
    </a>
    <a href="{{ route('surat-keluar.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'surat-keluar') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-paper-plane me-1"></i> Surat Keluar
    </a>
    <a href="{{ route('kategori-surat.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'kategori-surat') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-tags me-1"></i> Kategori Surat
    </a>
</div>
