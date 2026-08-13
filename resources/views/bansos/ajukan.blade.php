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
    Yang berwarna <span class="badge bg-success">hijau</span> berarti sudah dipilih. Fitur ini <strong>sementara</strong>, bisa berubah sewaktu-waktu.
</div>

<div class="p-4 bg-white rounded shadow">
    <p class="mb-3">Terpilih: <span id="jumlahTerpilih" class="fw-bold">{{ count($idSudahDiajukan) }}</span> / {{ \App\Http\Controllers\BansosController::MAKS_PER_KELAS }}</p>

    <form method="POST" action="{{ route('bansos.simpan-ajuan') }}" id="formBansos">
        @csrf
        <div id="wadahSiswa" class="d-flex flex-column gap-2 mb-3">
            @foreach ($siswa as $s)
                @php $terpilih = in_array($s->id_member, $idSudahDiajukan); @endphp
                <div class="siswa-bansos p-3 rounded border {{ $terpilih ? 'bg-success text-white' : 'bg-light' }}"
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
    const label = document.getElementById('jumlahTerpilih');
    const wadahInput = document.getElementById('wadahInputTersembunyi');
    let terpilih = new Set(@json($idSudahDiajukan));

    function renderInput() {
        wadahInput.innerHTML = '';
        terpilih.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'siswa[]';
            input.value = id;
            wadahInput.appendChild(input);
        });
        label.textContent = terpilih.size;
    }

    function updateTampilan() {
        document.querySelectorAll('.siswa-bansos').forEach(el => {
            const id = parseInt(el.dataset.id);
            const dipilih = terpilih.has(id);
            const icon = el.querySelector('i');

            el.classList.toggle('bg-success', dipilih);
            el.classList.toggle('text-white', dipilih);
            el.classList.toggle('bg-light', !dipilih);
            icon.classList.toggle('fa-check-circle', dipilih);
            icon.classList.toggle('fa-circle', !dipilih);

            // Kalau sudah penuh & baris ini belum dipilih, matikan klik-nya
            const penuh = terpilih.size >= maks;
            if (!dipilih && penuh) {
                el.style.opacity = '0.5';
                el.style.cursor = 'not-allowed';
                el.dataset.terkunci = '1';
            } else {
                el.style.opacity = '1';
                el.style.cursor = 'pointer';
                el.dataset.terkunci = '0';
            }
        });
    }

    document.querySelectorAll('.siswa-bansos').forEach(el => {
        el.addEventListener('click', function () {
            const id = parseInt(this.dataset.id);
            if (terpilih.has(id)) {
                terpilih.delete(id);
            } else {
                if (terpilih.size >= maks) return; // sudah penuh, abaikan klik
                terpilih.add(id);
            }
            renderInput();
            updateTampilan();
        });
    });

    updateTampilan();
});
</script>
@endsection
