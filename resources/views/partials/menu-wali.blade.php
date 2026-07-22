@php $rute = request()->route()->getName(); @endphp

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-home me-1"></i> Home
    </a>
    <a href="{{ route('guru.wali-siswa') }}" class="btn btn-sm {{ $rute === 'guru.wali-siswa' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-user-friends me-1"></i> List Siswa
    </a>
    <a href="{{ route('guru.wali-whatsapp') }}" class="btn btn-sm {{ $rute === 'guru.wali-whatsapp' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fab fa-whatsapp me-1"></i> Manajemen WhatsApp
    </a>
    <a href="{{ route('pendampingan.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'pendampingan.') && $rute !== 'pendampingan.galeri' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-hands-helping me-1"></i> Pendampingan
    </a>
    <a href="{{ route('pendampingan.galeri') }}" class="btn btn-sm {{ $rute === 'pendampingan.galeri' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-images me-1"></i> Galeri Wali
    </a>
</div>
