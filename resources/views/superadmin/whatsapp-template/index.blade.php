@extends('layouts.adminlte')

@section('title', 'Template Balasan Bot')

@section('content')
@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="card">
    <div class="card-header"><h3 class="card-title">Template Balasan Bot WhatsApp</h3></div>
    <div class="card-body">
        <p class="text-muted small">
            Semua kalimat yang dibalas bot di alur registrasi & absen ada di sini. Kode template tidak
            bisa diubah/ditambah (sudah dipakai langsung di kode), tapi isi teksnya bebas diedit. Placeholder
            seperti <code>{nama}</code>, <code>{kelas}</code> akan otomatis diganti data asli - jangan dihapus
            kalau masih dipakai.
        </p>
        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead><tr><th>Kode</th><th>Kapan Dipakai</th><th style="width:90px">Aksi</th></tr></thead>
            <tbody>
                @foreach ($template as $t)
                    <tr>
                        <td><code>{{ $t->kode }}</code></td>
                        <td>{{ $t->keterangan }}</td>
                        <td>
                            <a href="{{ route('superadmin.whatsapp-template.edit', $t) }}" class="btn btn-xs btn-outline-primary">
                                <i class="fas fa-edit"></i> Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        </div>
    </div>
</div>
@endsection
