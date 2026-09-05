@extends('layouts.adminlte')

@section('title', 'Konfirmasi Foto - ' . $kelas)

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Konfirmasi Foto - Kelas {{ $kelas }}</h3></div>
    <div class="card-body">
        <div class="alert alert-info">
            Diurutkan dari yang paling mirip namanya. Centang yang sudah benar, perbaiki dulu nama siswanya kalau
            tebakan salah (pakai dropdown), lalu klik Simpan di bawah. Yang tidak dicentang tidak akan disimpan.
        </div>

        <form method="POST" action="{{ route('superadmin.upload-foto-kelas.simpan-konfirmasi') }}">
            @csrf
            <input type="hidden" name="kelas" value="{{ $kelas }}">
            <input type="hidden" name="sesi_id" value="{{ $sesiId }}">

            <div class="row">
                @foreach ($hasil as $i => $h)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <img src="{{ Storage::url($h['path_sementara']) }}" class="card-img-top" style="height:160px;object-fit:cover;">
                            <div class="card-body p-2">
                                <p class="small text-muted mb-1 text-truncate" title="{{ $h['nama_file_asli'] }}">
                                    {{ $h['nama_file_asli'] }}
                                </p>
                                <p class="small mb-1">
                                    @if ($h['terdeteksi'])
                                        Kecocokan: <strong>{{ $h['skor'] }}%</strong>
                                    @else
                                        <span class="text-danger">Tidak terdeteksi</span> - pilih manual
                                    @endif
                                </p>
                                <select name="konfirmasi[{{ $i }}][id_siswa]" class="form-control form-control-sm mb-2">
                                    <option value="">- Pilih siswa -</option>
                                    @foreach ($siswaKelas as $s)
                                        <option value="{{ $s->id_member }}" @selected($s->id_member == $h['id_siswa_tebakan'])>
                                            {{ $s->nama_lengkap }}
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="konfirmasi[{{ $i }}][path_sementara]" value="{{ $h['path_sementara'] }}">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" class="custom-control-input cek-simpan" id="cek{{ $i }}"
                                           data-index="{{ $i }}" @checked($h['terdeteksi'])>
                                    <label class="custom-control-label" for="cek{{ $i }}">Simpan foto ini</label>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary mt-2"><i class="fas fa-save me-1"></i> Simpan yang Tercentang</button>
            <a href="{{ route('superadmin.upload-foto-kelas.form') }}" class="btn btn-outline-secondary mt-2">Batal</a>
        </form>
    </div>
</div>

<script>
// Kalau tidak dicentang, hapus dulu input konfirmasinya sebelum submit -
// biar yang tidak dicentang benar-benar tidak ikut disimpan.
document.querySelector('form').addEventListener('submit', function () {
    document.querySelectorAll('.cek-simpan').forEach(function (cek) {
        if (!cek.checked) {
            const idx = cek.dataset.index;
            document.querySelectorAll(`[name="konfirmasi[${idx}][id_siswa]"], [name="konfirmasi[${idx}][path_sementara]"]`)
                .forEach(function (el) { el.disabled = true; });
        }
    });
});
</script>
@endsection
