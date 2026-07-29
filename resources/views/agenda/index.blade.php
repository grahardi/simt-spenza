@extends('layouts.app')

@section('title', 'Agenda')

@section('content')
<div class="px-4 py-2 mb-3 text-white rounded shadow d-flex flex-column flex-md-row" style="background:#4b0082;">
    <div class="d-flex align-items-center me-md-auto">
        <i class="fas fa-calendar-alt fa-lg me-3"></i>
        <h1 class="h5 pt-2 mb-0">Agenda Kegiatan Sekolah</h1>
    </div>
    @if (auth('member')->user()->hasRole('kesiswaan'))
        <a href="{{ route('agenda.create') }}" class="btn btn-light btn-sm mt-2 mt-md-0">
            <i class="fas fa-plus me-1"></i> Tambah Agenda
        </a>
    @endif
</div>

@if (session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
@endif

<div class="d-flex flex-wrap gap-3 mb-3">
    @foreach (\App\Models\Agenda::KATEGORI_LABEL as $kode => $label)
        <div class="d-flex align-items-center gap-1">
            <span style="width:14px;height:14px;border-radius:3px;background:{{ \App\Models\Agenda::KATEGORI_WARNA[$kode] }};display:inline-block;"></span>
            <span class="small text-muted">{{ $label }}</span>
        </div>
    @endforeach
    <div class="d-flex align-items-center gap-1">
        <span style="width:14px;height:14px;border-radius:3px;background:#fde9ec;border:1px solid #f5c2c7;display:inline-block;"></span>
        <span class="small text-muted">Sabtu-Minggu (sekolah 5 hari kerja)</span>
    </div>
</div>

<div class="p-3 bg-white rounded shadow">
    <div id="kalenderAgenda"></div>
</div>

<div class="modal fade" id="modalDetailAgenda" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="judulDetailAgenda"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-2"><span class="badge" id="kategoriDetailAgenda"></span></p>
                <p class="mb-2"><i class="fas fa-calendar me-1 text-muted"></i> <span id="tanggalDetailAgenda"></span></p>
                <p class="mb-0" id="keteranganDetailAgenda"></p>
            </div>
            <div class="modal-footer" id="footerDetailAgenda"></div>
        </div>
    </div>
</div>

<link href="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const bisaEdit = @json(auth('member')->user()->hasRole('kesiswaan'));
    const calendarEl = document.getElementById('kalenderAgenda');
    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'id',
        height: 'auto',
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,listMonth' },
        buttonText: { today: 'Hari Ini', month: 'Bulan', list: 'Daftar' },
        events: '{{ route('agenda.data') }}',
        dayCellDidMount: function (info) {
            // Sabtu (6) & Minggu (0) diberi warna pink muda - sekolah cuma 5 hari kerja
            const hari = info.date.getDay();
            if (hari === 0 || hari === 6) {
                info.el.style.backgroundColor = '#fde9ec';
            }
        },
        eventClick: function (info) {
            document.getElementById('judulDetailAgenda').textContent = info.event.title;
            document.getElementById('kategoriDetailAgenda').textContent = info.event.extendedProps.kategori;
            document.getElementById('kategoriDetailAgenda').style.background = info.event.backgroundColor;
            document.getElementById('kategoriDetailAgenda').style.color = '#fff';

            const mulai = info.event.start.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
            let selesaiTampil = '';
            if (info.event.end) {
                const selesaiAsli = new Date(info.event.end);
                selesaiAsli.setDate(selesaiAsli.getDate() - 1); // balikin dari format eksklusif FullCalendar
                if (selesaiAsli.toDateString() !== info.event.start.toDateString()) {
                    selesaiTampil = ' s/d ' + selesaiAsli.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
                }
            }
            document.getElementById('tanggalDetailAgenda').textContent = mulai + selesaiTampil;
            document.getElementById('keteranganDetailAgenda').textContent = info.event.extendedProps.keterangan || '-';

            const footer = document.getElementById('footerDetailAgenda');
            footer.innerHTML = '';
            if (bisaEdit) {
                footer.innerHTML = `
                    <a href="/agenda/${info.event.id}/edit" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit me-1"></i> Edit</a>
                    <form method="POST" action="/agenda/${info.event.id}" class="d-inline" onsubmit="return confirm('Hapus agenda ini?')">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                        <input type="hidden" name="_method" value="DELETE">
                        <button type="submit" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i> Hapus</button>
                    </form>
                `;
            }

            new bootstrap.Modal(document.getElementById('modalDetailAgenda')).show();
        },
    });
    calendar.render();
});
</script>
@endsection
