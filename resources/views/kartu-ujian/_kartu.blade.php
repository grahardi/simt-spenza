@php
    $siswa = $p->siswa;
    $pengaturanKartu = \App\Models\PengaturanKartuUjian::ambil();
    $tanggalCetak = $pengaturanKartu->tanggal->translatedFormat('d F Y');
    $pengaturanSurat = \App\Models\PengaturanSurat::first();
@endphp
<div class="card">
    <div class="kop-img">
        <img src="{{ asset('images/kop-sekolah.png') }}" alt="Kop SMP Negeri 1 Turen">
    </div>

    <div class="title-kartu">{{ $pengaturanKartu->judul }}</div>

    <div class="content">
        <table>
            <tr>
                <td class="label">Nama</td><td class="separator">:</td>
                <td class="value">{{ $siswa->nama_lengkap }}</td>
            </tr>
            <tr>
                <td class="label">User</td><td class="separator">:</td>
                <td class="value">{{ $siswa->id_member }}</td>
            </tr>
            @if ($tampilkanPassword)
                <tr>
                    <td class="label">Password</td><td class="separator">:</td>
                    <td class="value">{{ $p->password ?? '-' }}</td>
                </tr>
            @endif
            <tr>
                <td class="label">Kls / Ruang</td><td class="separator">:</td>
                <td class="value">{{ $siswa->kelas }} / {{ $p->ruang ?? '-' }}</td>
            </tr>
            @if ($p->nokursi)
                <tr>
                    <td class="label">No. Kursi</td><td class="separator">:</td>
                    <td class="value">{{ $p->nokursi }}</td>
                </tr>
            @endif
        </table>
    </div>

    <div class="footer-kartu">
        <div class="foto-box">
            @if ($siswa->foto_url)
                <img src="{{ $siswa->foto_url }}" alt="Foto {{ $siswa->nama_lengkap }}">
            @else
                <div style="font-size: 7pt; color: #666; line-height: 1.2;">FOTO<br>3 x 4</div>
            @endif
        </div>
        <div class="ttd">
            <p>Turen, {{ $tanggalCetak }}</p>
            <p>Kepala Sekolah,</p>
            <img src="{{ asset('images/ttd-kepsek.png') }}" class="img-ttd" alt="TTD">
            <div class="ttd-nama">{{ $pengaturanSurat->kepsek_nama ?? '-' }}</div>
            <p>NIP. {{ $pengaturanSurat->kepsek_nip ?? '-' }}</p>
        </div>
    </div>
</div>
