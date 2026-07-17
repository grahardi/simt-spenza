@extends('layouts.app')

@section('title', 'Peminjaman Ruang Serbaguna')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow" style="background:#4b0082;">
    <h1 class="h5 pt-2 mb-0"><i class="fas fa-door-open me-2"></i>Peminjaman Ruang Serbaguna</h1>
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="px-4 py-3 mb-3 bg-white rounded shadow">
    <form method="GET" class="d-flex gap-2 align-items-center">
        <label class="form-label mb-0">Mulai dari tanggal</label>
        <input type="date" name="tgl" class="form-control" style="max-width:200px"
               value="{{ $mulai->format('Y-m-d') }}" onchange="this.form.submit()">
    </form>
</div>

<div class="p-4 bg-white rounded shadow">
    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>Jam</th>
                    @foreach ($tanggalList as $t)
                        <th>{{ $t->translatedFormat('D, d M') }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($jamTersedia as $jam)
                    <tr>
                        <td class="fw-semibold">{{ $jam }}</td>
                        @foreach ($tanggalList as $t)
                            @php $slot = $booking->get($t->toDateString().'-'.$jam)?->first(); @endphp
                            <td>
                                @if ($slot)
                                    <span class="badge-status" style="background:#fcebeb;color:#a32d2d;" title="{{ $slot->ket }}">
                                        {{ $slot->guru->nama ?? 'Dibooking' }}
                                    </span>
                                @else
                                    <a href="{{ route('smart.pinjam', [$t->toDateString(), $jam]) }}" class="btn btn-sm btn-outline-success">
                                        <i class="fas fa-plus"></i>
                                    </a>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
