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
    <i class="fas fa-info-circle me-1"></i> Klik nama siswa untuk memilih/batalkan, maksimal <strong>{{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }} siswa</strong>.
    <span class="badge bg-success">5 pertama = hijau</span>
    <span class="badge bg-warning text-dark">2 terakhir = kuning</span>
    Fitur ini <strong>sementara</strong>, bisa berubah sewaktu-waktu.
</div>

<div class="p-4 bg-white rounded shadow">
    <p class="mb-3">Terpilih: <span id="jumlahTerpilih" class="fw-bold">{{ count($idSudahDiajukan) }}</span> / {{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }}</p>

    <form method="POST" action="{{ route('bansos.simpan-ajuan') }}" id="formBansos">
        @csrf
        <div id="wadahSiswa" class="d-flex flex-column gap-2 mb-3">
            @php $idTerurut = array_values($idSudahDiajukan); @endphp
            @foreach ($siswa as $s)
                @php
                    $urutan = array_search($s->id_member, $idTerurut);
                    $terpilih = $urutan !== false;
                    $kelasWarna = !$terpilih ? 'bg-light' : ($urutan < \App\Http\Controllers\BansosController::JUMLAH_UTAMA ? 'bg-success text-white' : 'bg-warning text-dark');
                @endphp
                <div class="siswa-bansos p-3 rounded border {{ $kelasWarna }}"
                     data-id="{{ $s->id_member }}" role="button" style="cursor:pointer; user-select:none;">
                    <i class="fas fa-{{ $terpilih ? 'check-circle' : 'circle' }} me-2"></i>{{ $s->nama_lengkap }}
                </div>
            @endforeach
        </div>

        <div id="wadahInputTersembunyi">
            @foreach ($idSudahDiajukan as $id)
                <input type="hidden" name="siswa[]" value="{{ $id }}">
            @endforeach
        </div>

        <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan Ajuan</button>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const maks = {{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }};
    const jumlahUtama = {{ \App\Http\Controllers\BansosController::JUMLAH_UTAMA }};
    const label = document.getElementById('jumlahTerpilih');
    const wadahInput = document.getElementById('wadahInputTersembunyi');
    let terpilih = @json(array_values($idSudahDiajukan)); // array biar urutan pilih kejaga

    function renderInput() {
        wadahInput.innerHTML = '';
        terpilih.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'siswa[]';
            input.value = id;
            wadahInput.appendChild(input);
        });
        label.textContent = terpilih.length;
    }

    function updateTampilan() {
        document.querySelectorAll('.siswa-bansos').forEach(el => {
            const id = parseInt(el.dataset.id);
            const urutan = terpilih.indexOf(id); // -1 kalau belum dipilih
            const dipilih = urutan !== -1;
            const icon = el.querySelector('i');

            el.classList.remove('bg-success', 'bg-warning', 'bg-light', 'text-white', 'text-dark');
            if (dipilih && urutan < jumlahUtama) {
                el.classList.add('bg-success', 'text-white'); // 5 pertama = hijau
            } else if (dipilih) {
                el.classList.add('bg-warning', 'text-dark'); // 6-7 = kuning
            } else {
                el.classList.add('bg-light');
            }
            icon.classList.toggle('fa-check-circle', dipilih);
            icon.classList.toggle('fa-circle', !dipilih);

            const penuh = terpilih.length >= maks;
            if (!dipilih && penuh) {
                el.style.opacity = '0.5';
                el.style.cursor = 'not-allowed';
            } else {
                el.style.opacity = '1';
                el.style.cursor = 'pointer';
            }
        });
    }

    document.querySelectorAll('.siswa-bansos').forEach(el => {
        el.addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            const idx = terpilih.indexOf(id);
            if (idx !== -1) {
                terpilih.splice(idx, 1);
            } else {
                if (terpilih.length >= maks) return; // sudah penuh, abaikan klik
                terpilih.push(id);
            }
            renderInput();
            updateTampilan();
        });
    });

    updateTampilan();
});
</script>
@endsection
