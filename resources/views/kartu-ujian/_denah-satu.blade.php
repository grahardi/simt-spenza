<div class="header">
    <h3 class="m-0">DENAH TEMPAT DUDUK PESERTA UJIAN</h3>
    <h2 style="margin:5px 0">SMP NEGERI 1 TUREN</h2>
</div>
<div class="info-ruang">
    <span>RUANG: {{ $ruang }}</span>
    <span>TIPE: {{ strtoupper($tipeDenah) }}</span>
</div>
<div class="grid-denah">
    <div class="label-pintu">MEJA PENGAWAS / PAPAN TULIS</div>
    @foreach ($gridDenah as $baris)
        @foreach ($baris as $idx)
            @if ($idx !== null)
                @php $p = $peserta[$idx]; $s = $p->siswa; @endphp
                <div class="meja">
                    <div class="foto">
                        @if ($s->foto_url)
                            <img src="{{ $s->foto_url }}" alt="Foto">
                        @else
                            <span style="font-size:7pt">3x4</span>
                        @endif
                    </div>
                    <div class="user-id">{{ $s->id_member }}</div>
                    <div class="nama">{{ $s->nama_lengkap }}</div>
                    <div class="kelas">Kls: {{ $s->kelas }}</div>
                </div>
            @else
                <div class="empty-seat"></div>
            @endif
        @endforeach
    @endforeach
</div>
