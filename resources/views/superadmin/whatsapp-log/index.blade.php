@extends('layouts.adminlte')

@section('title', 'Log WhatsApp')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Log WhatsApp</h3>
        <div class="card-tools">
            <a href="{{ route('superadmin.whatsapp-log.index', ['arah' => 'keluar']) }}" class="btn btn-sm {{ $arah === 'keluar' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-arrow-up me-1"></i> Log Keluar
            </a>
            <a href="{{ route('superadmin.whatsapp-log.index', ['arah' => 'masuk']) }}" class="btn btn-sm {{ $arah === 'masuk' ? 'btn-primary' : 'btn-outline-primary' }}">
                <i class="fas fa-arrow-down me-1"></i> Log Masuk
            </a>
        </div>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-2 mb-3">
            <input type="hidden" name="arah" value="{{ $arah }}">
            <div class="col-md-3">
                <input type="text" name="nomor" class="form-control" placeholder="Cari nomor..." value="{{ request('nomor') }}">
            </div>
            @if ($arah === 'keluar')
                <div class="col-md-3">
                    <select name="status" class="form-control">
                        <option value="">Semua status</option>
                        <option value="berhasil" @selected(request('status') === 'berhasil')>Berhasil saja</option>
                        <option value="gagal" @selected(request('status') === 'gagal')>Gagal saja</option>
                    </select>
                </div>
            @endif
            <div class="col-md-2">
                <button type="submit" class="btn btn-secondary w-100"><i class="fas fa-search"></i></button>
            </div>
        </form>

        <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Waktu</th>
                    <th>Nomor</th>
                    <th>Sumber</th>
                    @if ($arah === 'keluar')
                        <th>API Diterima?</th>
                        <th>Status Pengiriman Sebenarnya</th>
                    @endif
                    <th>Isi Pesan</th>
                    @if ($arah === 'keluar')
                        <th>Detail Error</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($log as $l)
                    <tr>
                        <td class="text-nowrap">{{ $l->created_at->translatedFormat('d M Y, H:i:s') }}</td>
                        <td>{{ $l->nomor }}</td>
                        <td>{{ $l->sumber }}</td>
                        @if ($arah === 'keluar')
                            <td>
                                @if ($l->berhasil === true)
                                    <span class="badge badge-success">Berhasil</span>
                                @elseif ($l->berhasil === false)
                                    <span class="badge badge-danger">Gagal</span>
                                @else
                                    <span class="badge badge-secondary">-</span>
                                @endif
                            </td>
                            <td>
                                @if ($l->status_pengiriman)
                                    <span class="badge {{ $l->status_pengiriman === 'failed' ? 'badge-danger' : ($l->status_pengiriman === 'read' ? 'badge-primary' : 'badge-success') }}">
                                        {{ \App\Models\WhatsappLog::LABEL_STATUS_PENGIRIMAN[$l->status_pengiriman] ?? $l->status_pengiriman }}
                                    </span>
                                @else
                                    <span class="badge badge-secondary" title="Belum ada update status dari webhook Meta">Belum ada info</span>
                                @endif
                            </td>
                        @endif
                        <td style="max-width:300px; white-space:pre-wrap;">{{ $l->teks }}</td>
                        @if ($arah === 'keluar')
                            <td class="small text-danger">{{ $l->detail_error }}</td>
                        @endif
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted">Belum ada log.</td></tr>
                @endforelse
            </tbody>
        </table>
        </div>
        {{ $log->onEachSide(1)->links() }}
    </div>
</div>
@endsection
