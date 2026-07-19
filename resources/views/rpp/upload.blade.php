@extends('layouts.app')

@section('title', 'Upload RPP')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-book me-2"></i>Upload RPP</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif
@if ($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
@endif

<div class="p-4 bg-white rounded shadow mb-3" style="max-width:480px;">
    <form method="POST" action="{{ route('rpp.simpan') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Bulan</label>
            <select name="bulan" class="form-select" required>
                @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $b)
                    <option value="{{ $b }}">{{ $b }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">File RPP (PDF)</label>
            <input type="file" name="file" accept="application/pdf" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-upload me-1"></i> Upload</button>
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <h3 class="h6 mb-3">RPP yang Sudah Diupload</h3>
    @if ($daftar->isEmpty())
        <div class="text-muted text-center py-3">Belum ada RPP yang diupload.</div>
    @else
        <div class="table-responsive">
        <table class="table table-striped">
            <thead><tr><th>Bulan</th><th>Tanggal Upload</th><th>Status</th><th>File</th></tr></thead>
            <tbody>
                @foreach ($daftar as $r)
                    <tr>
                        <td>{{ $r->bulan }}</td>
                        <td>{{ $r->tanggal->translatedFormat('d F Y') }}</td>
                        <td>
                            @if ($r->sudahDisetujui())
                                <span class="badge-status" style="background:#eaf3de;color:#3b6d11;">Disetujui</span>
                            @else
                                <span class="badge-status" style="background:#faeeda;color:#854f0b;">Menunggu</span>
                            @endif
                        </td>
                        <td><a href="{{ Storage::url($r->namafile) }}" target="_blank" class="btn btn-sm btn-outline-secondary"><i class="fas fa-file-pdf"></i></a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    @endif
</div>
@endsection
