@if ($d->jadwal->isEmpty())
    <p class="text-muted small mb-0">Tidak ada jadwal mengajar untuk guru ini hari ini.</p>
@else
    <div class="d-flex flex-column gap-2">
        @php $warnaIndex = -1; $kunciSebelumnya = null; @endphp
        @foreach ($d->jadwal->sortBy('jamhari') as $j)
            @php
                $kunciSekarang = $j->kelas.'|'.$j->mapel;
                $blokBaru = $kunciSekarang !== $kunciSebelumnya;
                if ($blokBaru) { $warnaIndex++; }
                $kunciSebelumnya = $kunciSekarang;
                $warna = $palet[$warnaIndex % count($palet)];
                $tampilkanTombolTugas = $blokBaru && $d->record->status !== 'a';
                $tugasSudahAda = $tampilkanTombolTugas ? ($d->tugas[$j->kelas] ?? null) : null;
            @endphp
            <div class="jadwal-baris bg-{{ $warna }}">
                <span class="jadwal-jam-kecil">{{ $j->jamhari }}</span>
                <span class="jadwal-waktu-kecil">{{ $j->waktu ?? '-' }}</span>
                <span class="jadwal-kelas-kecil">{{ $j->kelas }}</span>
                <span class="jadwal-mapel-kecil">{{ $j->mapelLengkap() }}</span>
                @if ($tampilkanTombolTugas)
                    <a href="{{ route('tugas.upload', [$d->guru, $j->kelas]) }}?dari_piket=1" class="btn btn-sm btn-outline-dark" style="border-color:currentColor;color:inherit;">
                        @if ($tugasSudahAda)
                            <i class="fas fa-eye me-1"></i> Lihat Tugas
                        @else
                            <i class="far fa-folder-open me-1"></i> Tugas Kosong
                        @endif
                    </a>
                @endif
            </div>
        @endforeach
    </div>
@endif
<a href="{{ route('jadwal.guru', $d->guru) }}" class="btn btn-sm btn-outline-secondary mt-3">
    <i class="fas fa-calendar-alt me-1"></i> Lihat Halaman Lengkap Guru Ini
</a>
