@extends('layouts.app')

@section('title', 'Bimbingan - ' . $siswa->nama_lengkap)

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-hands-helping me-2"></i>Bimbingan Konseling</h1>
</div>

<div class="p-4 bg-white rounded shadow mx-auto" style="max-width:520px;">
    <div class="d-flex align-items-center gap-3 mb-4 pb-3 border-bottom">
        @if ($siswa->foto_url)
            <img src="{{ $siswa->foto_url }}" class="rounded-circle" style="width:56px;height:56px;object-fit:cover;">
        @else
            <span class="foto-siswa-placeholder" style="width:56px;height:56px;font-size:18px;">{{ $siswa->initials() }}</span>
        @endif
        <div>
            <div class="fw-bold">{{ $siswa->nama_lengkap }}</div>
            <div class="text-muted small">No. Induk {{ $siswa->id_member }} &middot; Kelas {{ $siswa->kelas }}</div>
        </div>
    </div>

    <form method="POST" action="{{ route('bimbingan.simpan', $siswa) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="form-label fw-semibold mb-2">Jenis</label>
            <div class="kategori-pilih">
                @foreach ([
                    'Pendampingan' => 'green',
                    'Verifikasi' => 'blue',
                    'Pelanggaran' => 'coral',
                    'Lainnya' => 'purple',
                ] as $kat => $warna)
                    <input type="radio" name="kategori" value="{{ $kat }}" id="kat{{ $kat }}" class="kategori-radio" @checked($loop->first) required>
                    <label for="kat{{ $kat }}" class="kategori-label bg-{{ $warna }}">{{ $kat }}</label>
                @endforeach
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Keterangan Pendampingan</label>
            <textarea name="keterangan" class="form-control" rows="3" placeholder="Ceritakan kondisinya..."></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-semibold">Aksi Selanjutnya</label>
            <select name="tindakan" class="form-select" required>
                @foreach (\App\Models\Bimbingan::TINDAKAN as $t)
                    <option value="{{ $t }}">{{ $t }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label class="form-label fw-semibold">Foto (opsional)</label>
            <input type="file" name="foto" accept="image/*" class="form-control">
        </div>
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-save me-1"></i> Simpan</button>
            <a href="{{ route('bimbingan.cari') }}" class="btn btn-outline-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
