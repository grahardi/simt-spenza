@extends('layouts.adminlte')

@section('title', 'Konfirmasi Data PIP')

@section('content')
<div class="card">
    <div class="card-header"><h3 class="card-title">Konfirmasi NISN Tidak Ketemu</h3></div>
    <div class="card-body">
        <div class="alert alert-success">{{ $langsungTersimpan }} data berhasil diimport otomatis (NISN cocok).</div>
        <div class="alert alert-warning">
            {{ count($hasilKemiripan) }} data NISN-nya <strong>tidak ketemu</strong> di Data Siswa. Cocokkan manual lewat nama di bawah (diurutkan dari yang paling mirip), centang yang mau ikut disimpan.
        </div>

        <form method="POST" action="{{ route('superadmin.pip.simpan-kemiripan') }}">
            @csrf
            <div class="table-responsive">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th style="width:30px"></th>
                        <th>Data dari Excel</th>
                        <th>Cocokkan ke Siswa</th>
                        <th>Kecocokan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($hasilKemiripan as $i => $h)
                        <tr>
                            <td class="text-center">
                                <input type="checkbox" class="cek-simpan" data-index="{{ $i }}" @checked($h['skor'] >= 60)>
                            </td>
                            <td>
                                <strong>{{ $h['dataPip']['nama_pd'] }}</strong><br>
                                <span class="text-muted small">Kelas Excel: {{ $h['dataPip']['kelas_excel'] }}</span>
                            </td>
                            <td>
                                <select name="konfirmasi[{{ $i }}][id_siswa]" class="form-control form-control-sm">
                                    <option value="">- Pilih siswa -</option>
                                    @foreach ($semuaSiswa as $s)
                                        <option value="{{ $s->id_member }}" @selected($s->id_member == $h['idSiswaTebakan'])>
                                            {{ $s->nama_lengkap }} ({{ $s->kelas }})
                                        </option>
                                    @endforeach
                                </select>
                                <input type="hidden" name="konfirmasi[{{ $i }}][nisn]" value="{{ $h['dataPip']['nisn'] }}">
                                <input type="hidden" name="konfirmasi[{{ $i }}][data_pip]" value='{{ json_encode($h['dataPip']) }}'>
                            </td>
                            <td>{{ $h['skor'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-1"></i> Simpan yang Tercentang</button>
            <a href="{{ route('superadmin.pip.form') }}" class="btn btn-outline-secondary">Kembali</a>
        </form>
    </div>
</div>

<script>
document.querySelectorAll('.cek-simpan').forEach(function (cek) {
    cek.addEventListener('change', function () {
        const idx = this.dataset.index;
        const els = document.querySelectorAll(`[name="konfirmasi[${idx}][id_siswa]"], [name="konfirmasi[${idx}][nisn]"], [name="konfirmasi[${idx}][data_pip]"]`);
        els.forEach(el => el.disabled = !this.checked);
    });
    cek.dispatchEvent(new Event('change'));
});
</script>
@endsection
