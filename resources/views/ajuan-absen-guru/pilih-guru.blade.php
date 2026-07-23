@extends('layouts.app')

@section('title', 'Ajuan Piket Guru')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-user-clock me-2"></i>Ajuan Piket Guru - Pilih Guru</h1>
</div>

<div class="p-4 bg-white rounded shadow" style="max-width:480px;">
    <p class="text-muted small">Pilih guru yang mau diajukan absennya (Sakit/Ijin/Dispensasi).</p>
    <form method="GET" onsubmit="event.preventDefault(); window.location = '{{ url('/ajuan-piket-guru') }}/' + document.getElementById('pilihGuru').value;">
        <div class="mb-3">
            <select id="pilihGuru" class="form-select" required>
                <option value="">- Pilih guru -</option>
                @foreach ($daftarGuru as $g)
                    <option value="{{ $g->id_guru }}">{{ $g->nama }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-arrow-right me-1"></i> Lanjut</button>
    </form>
</div>
@endsection
