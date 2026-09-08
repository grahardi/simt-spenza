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
        @foreach ($baris as $idxMeja)
            @if ($idxMeja !== null)
                @php $grupSiswa = $kelompokMeja[$idxMeja]; @endphp
                <div class="meja">
                    <div class="label-meja">MEJA {{ $idxMeja + 1 }}</div>
                    <div class="meja-isi">
                        @foreach ($grupSiswa as $p)
                            @php $s = $p->siswa; @endphp
                            <div class="kartu-mini">
                                <div class="foto">
                                    @if ($s->foto_url)
                                        <img src="{{ $s->foto_url }}" alt="Foto">
                                    @else
                                        <span style="font-size:7pt">3x4</span>
                                    @endif
                                </div>
                                <div class="user-id">{{ $s->id_member }}</div>
                                <div class="nama">{{ $s->nama_lengkap }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="empty-seat"></div>
            @endif
        @endforeach
    @endforeach
</div>
