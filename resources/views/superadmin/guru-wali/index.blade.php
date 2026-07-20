@extends('layouts.adminlte')

@section('title', 'Guru Wali')

@section('content')
@if (session('status'))
    <div class="alert alert-info">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header"><h3 class="card-title">Guru Wali - Assign Siswa</h3></div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <div class="col-md-4">
                <input type="text" name="cari" class="form-control" placeholder="Cari nama siswa..." value="{{ request('cari') }}">
            </div>
            <div class="col-md-3">
                <select name="kelas" class="form-control">
                    <option value="">Semua kelas</option>
                    @foreach ($daftarKelas as $k)
                        <option value="{{ $k }}" @selected(request('kelas') === $k)>{{ $k }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-control">
                    <option value="">Semua status</option>
                    <option value="belum" @selected(request('status') === 'belum')>Belum ada wali</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-search"></i> Cari</button>
            </div>
        </form>

        <form method="POST" action="{{ route('superadmin.guru-wali.assign') }}">
            @csrf
            <div class="d-flex align-items-center gap-2 mb-3 p-2 bg-light rounded">
                <label class="mb-0">Assign siswa terpilih ke wali:</label>
                <select name="id_guru" class="form-control" style="max-width:320px" required>
                    <option value="">- Pilih guru -</option>
                    @foreach ($daftarGuru as $g)
                        <option value="{{ $g->id_guru }}">{{ $g->nama }}</option>
                    @endforeach
                </select>
                <button type="submit" class="btn btn-success"><i class="fas fa-user-check me-1"></i> Assign</button>
            </div>

            <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:30px"><input type="checkbox" onclick="document.querySelectorAll('.cb-siswa').forEach(c=>c.checked=this.checked)"></th>
                        <th>No. Induk</th>
                        <th>Nama Siswa</th>
                        <th>Kelas</th>
                        <th>Guru Wali Saat Ini</th>
                        <th style="width:100px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($siswa as $s)
                        <tr>
                            <td><input type="checkbox" name="siswa_id[]" value="{{ $s->id_member }}" class="cb-siswa"></td>
                            <td>{{ $s->id_member }}</td>
                            <td>{{ $s->nama_lengkap }}</td>
                            <td>{{ $s->kelas }}</td>
                            <td>
                                @if ($s->guruWali)
                                    <span class="badge badge-success">{{ $s->guruWali->nama }}</span>
                                @else
                                    <span class="text-muted">Belum ada</span>
                                @endif
                            </td>
                            <td>
                                @if ($s->guruWali)
                                    <button type="button" class="btn btn-xs btn-outline-danger" form="form-lepas-{{ $s->id_member }}" onclick="document.getElementById('form-lepas-{{ $s->id_member }}').submit();return false;">
                                        Lepas
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted">Data siswa tidak ditemukan.</td></tr>
                    @endforelse
                </tbody>
            </table>
            </div>
        </form>

        @foreach ($siswa as $s)
            @if ($s->guruWali)
                <form id="form-lepas-{{ $s->id_member }}" method="POST" action="{{ route('superadmin.guru-wali.lepas', $s) }}" class="d-none">
                    @csrf
                    @method('DELETE')
                </form>
            @endif
        @endforeach

        {{ $siswa->onEachSide(1)->links() }}
    </div>
</div>
@endsection
