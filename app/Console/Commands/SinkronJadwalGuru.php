<?php

namespace App\Console\Commands;

use App\Models\DataJadwal;
use App\Models\Guru;
use App\Models\KodeGuru;
use Illuminate\Console\Command;

class SinkronJadwalGuru extends Command
{
    /**
     * php artisan jadwal:sinkron           -> cocokkan & tampilkan laporan (aman, tidak ubah data)
     * php artisan jadwal:sinkron --apply    -> setelah dicek laporannya, isi tabel datajadwal beneran
     *
     * PENTING (revisi): kode di sheet "kodeguru" Excel (02, 03, dst) BUKAN
     * kode acak yang perlu dicocokkan lewat nama - itu adalah id_guru ASLI
     * (cuma ditulis 2 digit, "02" = id_guru 2), dikonfirmasi lewat member.sql
     * asli (id_guru=2 -> Anik Asri Wijayati, cocok persis dengan kode "02").
     * Versi sebelumnya salah pakai fuzzy-match nama padahal harusnya tinggal
     * di-cast jadi angka - itu yang bikin sinkronisasi jadwal kemarin meleset.
     * Sekarang kode dipakai LANGSUNG sebagai id_guru, nama cuma dipakai untuk
     * verifikasi/peringatan kalau ternyata tidak cocok (bukan basis pencarian).
     */
    protected $signature = 'jadwal:sinkron {--apply : Simpan hasil ke tabel datajadwal, bukan cuma pratinjau} {--reset : Kosongkan dulu tabel datajadwal sebelum diisi ulang (dipakai bareng --apply)}';

    protected $description = 'Kode di Excel = id_guru asli (bukan hasil fuzzy-match). Cek & isi datajadwal.';

    public function handle(): int
    {
        $referensi = require database_path('data/kodeguru_reference.php');
        $daftarGuru = Guru::all(['id_guru', 'nama'])->keyBy('id_guru');

        if ($daftarGuru->isEmpty()) {
            $this->error('Tabel guru masih kosong - pastikan data guru sudah ada sebelum sinkronisasi jadwal.');
            return self::FAILURE;
        }

        $hasil = [];
        foreach ($referensi as $kode => $data) {
            $idGuru = (int) $kode; // "02" -> 2, "51" -> 51
            $guruAsli = $daftarGuru->get($idGuru);

            $namaExcel = $this->normalisasiNama($data['nama']);
            $namaDb = $guruAsli ? $this->normalisasiNama($guruAsli->nama) : null;
            similar_text($namaExcel, $namaDb ?? '', $persen);

            $hasil[] = [
                'kode' => $kode,
                'id_guru' => $idGuru,
                'nama_excel' => $data['nama'],
                'mapel' => $data['mapel'],
                'nama_guru_db' => $guruAsli->nama ?? null,
                'ditemukan' => (bool) $guruAsli,
                'skor' => (int) round($persen),
            ];

            KodeGuru::updateOrCreate(
                ['kode' => $kode],
                [
                    'nama_excel' => $data['nama'],
                    'mapel' => $data['mapel'],
                    // id_guru dipakai langsung dari kode - HANYA null kalau
                    // id_guru itu memang tidak ada sama sekali di tabel guru.
                    'id_guru' => $guruAsli ? $idGuru : null,
                    'skor_kecocokan' => (int) round($persen),
                ]
            );
        }

        $this->table(
            ['Kode', 'id_guru', 'Nama di Excel', 'Nama di DB (id_guru ini)', 'Skor Nama %', 'Status'],
            collect($hasil)->map(fn ($h) => [
                $h['kode'],
                $h['id_guru'],
                $h['nama_excel'],
                $h['nama_guru_db'] ?? '(id_guru tidak ditemukan di tabel guru)',
                $h['skor'],
                !$h['ditemukan'] ? '❌ id_guru tidak ada' : ($h['skor'] < 60 ? '⚠️  Nama beda jauh, cek manual' : '✅ Cocok'),
            ])
        );

        $tidakDitemukan = collect($hasil)->where('ditemukan', false)->count();
        $namaMeleset = collect($hasil)->where('ditemukan', true)->where('skor', '<', 60)->count();

        $this->newLine();
        $this->info(count($hasil).' kode diproses. '.$tidakDitemukan.' id_guru tidak ditemukan di tabel guru, '.$namaMeleset.' ditemukan tapi namanya beda jauh dari Excel (kemungkinan id_guru sudah dipakai ulang untuk guru baru - cek manual).');

        if ($tidakDitemukan > 0) {
            $this->newLine();
            $this->warn('Daftar kode yang id_guru-nya TIDAK ditemukan di tabel guru:');
            collect($hasil)->where('ditemukan', false)->each(function ($h) {
                $this->line("  - Kode {$h['kode']} (id_guru {$h['id_guru']}): {$h['nama_excel']} [{$h['mapel']}]");
            });
            $this->newLine();
            $this->comment('Baris jadwal kode-kode di atas TETAP akan dimasukkan ke datajadwal (--apply), nama gurunya tampil "-" sampai ditambahkan ke Data Guru dengan id_guru yang sama.');
        }

        if (!$this->option('apply')) {
            $this->newLine();
            $this->comment('Ini baru PRATINJAU. Jalankan ulang dengan --apply untuk benar-benar mengisi tabel datajadwal.');
            return self::SUCCESS;
        }

        $this->newLine();
        $this->info('Mengisi tabel datajadwal dari jadwal_matrix.php...');

        if ($this->option('reset')) {
            \App\Models\DataJadwal::truncate();
            $this->warn('Tabel datajadwal dikosongkan dulu (--reset) sebelum diisi ulang.');
        }

        $matrix = require database_path('data/jadwal_matrix.php');

        $dimasukkan = 0;
        $idBerikutnya = (int) (DataJadwal::max('id') ?? 0) + 1;

        \Illuminate\Support\Facades\DB::transaction(function () use ($matrix, &$dimasukkan, &$idBerikutnya) {
            foreach ($matrix as $baris) {
                // Kode di Excel SELALU dipakai langsung sebagai kodeguru, walau
                // id_guru itu belum/tidak ada di tabel `guru` master - baris
                // jadwal tetap masuk supaya jadwalnya lengkap. Nama guru yang
                // belum ada di tabel `guru` akan tampil '-' di halaman Jadwal
                // sampai datanya dilengkapi di Data Guru.
                $idGuru = (int) $baris['kode'];

                $mapel = KodeGuru::where('kode', $baris['kode'])->value('mapel');

                $existing = DataJadwal::where('hari', $baris['hari'])
                    ->where('jamhari', $baris['jam'])
                    ->where('kelas', $baris['kelas'])
                    ->first();

                if ($existing) {
                    $existing->update(['kodejam' => $baris['jam'], 'kodeguru' => $idGuru, 'mapel' => $mapel]);
                } else {
                    // Kolom id di tabel datajadwal BUKAN auto-increment - harus
                    // diisi manual, kalau tidak MySQL menolak insert-nya.
                    DataJadwal::create([
                        'id' => $idBerikutnya++,
                        'hari' => $baris['hari'],
                        'jamhari' => $baris['jam'],
                        'kelas' => $baris['kelas'],
                        'kodejam' => $baris['jam'],
                        'kodeguru' => $idGuru,
                        'mapel' => $mapel,
                    ]);
                }
                $dimasukkan++;
            }
        });

        $this->info("Selesai: {$dimasukkan} jadwal masuk (semua baris dimasukkan, termasuk yang id_guru-nya belum ada di tabel guru).");

        return self::SUCCESS;
    }

    private function normalisasiNama(string $nama): string
    {
        $nama = strtolower($nama);
        $nama = str_replace(',', ' ', $nama);
        $gelar = ['drs', 'dra', 'dr', 'prof', 'ir', 's.pd', 's.ag', 's.kom', 's.si', 's.s', 's.psi', 's.sn', 's.pdi', 'a.ma.pd', 'ama.pd', 'm.pd', 'm.psi', 'gr'];
        foreach ($gelar as $g) {
            $nama = preg_replace('/\b'.preg_quote($g, '/').'\b/i', '', $nama);
        }
        $nama = preg_replace('/[^a-z ]/', '', $nama);
        return trim(preg_replace('/\s+/', ' ', $nama));
    }
}
