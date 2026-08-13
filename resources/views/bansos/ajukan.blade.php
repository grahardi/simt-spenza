@extends('layouts.app')

@section('title', 'Ajukan Bansos - ' . $kelas)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hand-holding-heart me-2"></i>Ajukan Bansos - Kelas {{ $kelas }}</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="alert alert-info">
    <i class="fas fa-info-circle me-1"></i> Pilih maksimal <strong>{{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }} siswa</strong> di kelas ini untuk diajukan sebagai penerima Bansos.
    Fitur ini <strong>sementara</strong>, bisa berubah sewaktu-waktu.
</div>

<div class="p-4 bg-white rounded shadow">
    <form method="POST" action="{{ route('bansos.simpan-ajuan') }}">
        @csrf
        <p class="mb-2">
            Terpilih: <span id="jumlahTerpilih">{{ count($idSudahDiajukan) }}</span> / {{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }}
        </p>

        <div class="list-group mb-3">
            @foreach ($siswa as $s)
                <label class="list-group-item d-flex align-items-center gap-2">
                    <input type="checkbox" name="siswa[]" value="{{ $s->id_member }}" class="form-check-input cek-bansos"
                           @checked(in_array($s->id_member, $idSudahDiajukan))>
                    {{ $s->nama_lengkap }}
                </label>
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Ajuan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const maks = {{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }};
    const checkboxes = document.querySelectorAll('.cek-bansos');
    const label = document.getElementById('jumlahTerpilih');

    function update() {
        const dipilih = document.querySelectorAll('.cek-bansos:checked').length;
        label.textContent = dipilih;
        checkboxes.forEach(cb => {
            if (!cb.checked) cb.disabled = dipilih >= maks;
        });
    }

    checkboxes.forEach(cb => cb.addEventListener('change', update));
    update();
});
</script>
@endsection
