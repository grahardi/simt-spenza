<?php

namespace App\Console\Commands;

use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\KodeGuru;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SinkronJadwalGuru extends Command
{
    /**
     * php artisan jadwal:sinkron           -> cuma cocokkan nama & tampilkan laporan (aman, tidak ubah data)
     * php artisan jadwal:sinkron --apply    -> setelah dicek laporannya, isi tabel datajadwal beneran
     */
    protected $signature = 'jadwal:sinkron {--apply : Simpan hasil ke tabel datajadwal, bukan cuma pratinjau}';

    protected $description = 'Cocokkan kode guru dari file Excel jadwal ke tabel guru asli (fuzzy match nama), lalu isi datajadwal';

    // Gelar/title akademik yang dibuang dulu sebelum membandingkan nama,
    // supaya "Ali Mahfud, M.Pd" bisa cocok dengan "ALI MAHFUD" di database.
    private array $gelarDibuang = [
        'drs', 'dra', 'dr', 'prof', 'ir',
        's.pd', 's.pd.', 's.ag', 's.ag.', 's.kom', 's.kom.', 's.si', 's.si.',
        's.s', 's.s.', 's.psi', 's.psi.', 's.sn', 's.sn.', 's.pdi', 's.pdi.',
        'a.ma.pd', 'a.ma.pd.', 'm.pd', 'm.pd.', 'm.psi', 'gr', 'gr.',
    ];

    public function handle(): int
    {
        $referensi = require database_path('data/kodeguru_reference.php');
        $daftarGuru = Guru::all(['id_guru', 'nama']);

        if ($daftarGuru->isEmpty()) {
            $this->error('Tabel guru masih kosong - pastikan data guru sudah ada sebelum sinkronisasi jadwal.');
            return self::FAILURE;
        }

        $hasil = [];
        foreach ($referensi as $kode => $data) {
            [$idGuruCocok, $skor, $namaCocok] = $this->cariGuruPalingCocok($data['nama'], $daftarGuru);

            $hasil[] = [
                'kode' => $kode,
                'nama_excel' => $data['nama'],
                'mapel' => $data['mapel'],
                'id_guru' => $idGuruCocok,
                'nama_guru_db' => $namaCocok,
                'skor' => $skor,
            ];

            KodeGuru::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama_excel' => $data['nama'],
                    'mapel' => $data['mapel'],
                    'id_guru' => $skor >= 60 ? $idGuruCocok : null,
                    'skor_kecocokan' => $skor,
                ]
            );
        }

        $this->table(
            ['Kode', 'Nama di Excel', 'Nama Tercocok di DB', 'Skor %', 'Status'],
            collect($hasil)->map(fn ($h) => [
                $h['kode'],
                $h['nama_excel'],
                $h['nama_guru_db'] ?? '-',
                $h['skor'],
                $h['skor'] >= 60 ? '✅ Cocok' : '⚠️  Perlu cek manual',
            ])
        );

        $butuhCek = collect($hasil)->where('skor', '<', 60)->count();
        $this->newLine();
        $this->info(count($hasil).' kode diproses, '.($count = count($hasil) - $butuhCek).' cocok otomatis (skor >= 60%), '.$butuhCek.' perlu dicek manual.');
        $this->line('Hasil pencocokan tersimpan di tabel `kodeguru` - boleh dikoreksi manual (isi kolom id_guru) sebelum lanjut --apply.');

        if (!$this->option('apply')) {
            $this->newLine();
            $this->comment('Ini baru PRATINJAU. Jalankan ulang dengan --apply untuk benar-benar mengisi tabel datajadwal.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Mengisi tabel datajadwal dari jadwal_matrix.php...');

        $matrix = require database_path('data/jadwal_matrix.php');
        $peta = KodeGuru::whereNotNull('id_guru')->pluck('id_guru', 'kode');

        $dilewati = 0;
        $dimasukkan = 0;

        DB::transaction(function () use ($matrix, $peta, &$dilewati, &$dimasukkan) {
            foreach ($matrix as $baris) {
                $idGuru = $peta->get($baris['kode']);

                // Kode yang tidak berhasil dicocokkan ke guru manapun (skor < 60,
                // atau memang tidak ada di data guru sama sekali) - dilewati saja,
                // termasuk kode yang ternyata bukan guru sungguhan.
                if (!$idGuru) {
                    $dilewati++;
                    continue;
                }

                $mapel = KodeGuru::where('kode', $baris['kode'])->value('mapel');

                DataJadwal::updateOrCreate(
                    ['hari' => $baris['hari'], 'jamhari' => $baris['jam'], 'kelas' => $baris['kelas']],
                    ['kodejam' => $baris['jam'], 'kodeguru' => $idGuru, 'mapel' => $mapel]
                );
                $dimasukkan++;
            }
        });

        $this->info("Selesai: {$dimasukkan} jadwal masuk, {$dilewati} baris dilewati (kode guru tidak cocok/tidak ditemukan).");

        return self::SUCCESS;
    }

    private function normalisasiNama(string $nama): string
    {
        $nama = strtolower($nama);
        $nama = str_replace(',', ' ', $nama);
        $nama = preg_replace('/\s+/', ' ', $nama);

        foreach ($this->gelarDibuang as $gelar) {
            $nama = preg_replace('/\b'.preg_quote($gelar, '/').'\b/i', '', $nama);
        }

        $nama = preg_replace('/[^a-z ]/', '', $nama);
        $nama = trim(preg_replace('/\s+/', ' ', $nama));

        return $nama;
    }

    /** @return array{0: ?int, 1: int, 2: ?string} [id_guru, skor 0-100, nama_di_db] */
    private function cariGuruPalingCocok(string $namaExcel, $daftarGuru): array
    {
        $target = $this->normalisasiNama($namaExcel);
        $terbaikId = null;
        $terbaikNama = null;
        $terbaikSkor = 0;

        foreach ($daftarGuru as $guru) {
            $kandidat = $this->normalisasiNama($guru->nama);
            similar_text($target, $kandidat, $persen);

            if ($persen > $terbaikSkor) {
                $terbaikSkor = $persen;
                $terbaikId = $guru->id_guru;
                $terbaikNama = $guru->nama;
            }
        }

        return [$terbaikId, (int) round($terbaikSkor), $terbaikNama];
    }
}
