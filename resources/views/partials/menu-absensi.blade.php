@php $rute = request()->route()->getName(); @endphp

<div class="d-flex flex-wrap gap-2 mb-3">
    <a href="{{ route('dashboard') }}" class="btn btn-sm btn-outline-secondary">
        <i class="fas fa-home me-1"></i> Home
    </a>
    <a href="{{ route('absensi.index') }}" class="btn btn-sm {{ $rute === 'absensi.index' ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fas fa-clipboard-check me-1"></i> Absensi Siswa
    </a>
    @if (auth('member')->user()->hasRole('piket'))
        <a href="{{ route('absensi.isi') }}" class="btn btn-sm {{ $rute === 'absensi.isi' ? 'btn-primary' : 'btn-outline-primary' }}">
            <i class="fas fa-pen me-1"></i> Isi Absensi
        </a>
    @endif
    <a href="{{ route('ajuan-whatsapp.index') }}" class="btn btn-sm {{ str_starts_with($rute, 'ajuan-whatsapp') ? 'btn-primary' : 'btn-outline-primary' }}">
        <i class="fab fa-whatsapp me-1"></i> Ajuan WhatsApp
    </a>
</div>
