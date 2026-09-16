<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PipSiswa;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PipController extends Controller
{
    public function form()
    {
        return view('superadmin.pip.form');
    }

    /**
     * Step 1: baca Excel, cocokkan tiap baris ke siswa lewat NISN (bukan nama).
     * Yang NISN-nya ketemu langsung disimpan. Yang TIDAK ketemu ditampilkan di
     * tabel kemiripan (kiri: data Excel, kanan: tebakan siswa berdasar kemiripan
     * NAMA - dropdown - buat dikonfirmasi manual) sebelum ikut disimpan.
     */
    public function unggah(Request $request)
    {
        $request->validate(['file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:10240']]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file_excel')->getRealPath());
        $baris = $spreadsheet->getActiveSheet()->toArray();

        // Baris 1-2 judul/nama sekolah, baris 3 header kolom, data mulai baris 4.
        $header = array_map(fn ($h) => trim((string) $h), $baris[2] ?? []);
        $dataBaris = array_slice($baris, 3);

        $kolom = array_flip($header); // nama_kolom => index

        $langsungTersimpan = 0;
        $tidakDitemukan = [];

        foreach ($dataBaris as $r) {
            $nisn = trim((string) ($r[$kolom['nisn'] ?? -1] ?? ''));
            if ($nisn === '') {
                continue;
            }

            $siswa = Siswa::where('nisn', $nisn)->first();
            $dataPip = $this->ekstrakDataPip($r, $kolom);

            if ($siswa) {
                PipSiswa::updateOrCreate(['nisn' => $nisn], array_merge($dataPip, ['id_siswa' => $siswa->id_member]));
                $langsungTersimpan++;
            } else {
                $tidakDitemukan[] = array_merge($dataPip, ['nisn' => $nisn]);
            }
        }

        if (empty($tidakDitemukan)) {
            return redirect()->route('superadmin.pip.form')->with('status', "{$langsungTersimpan} data berhasil diimport (semua NISN cocok).");
        }

        // Buat tebakan kemiripan nama buat yang NISN-nya tidak ketemu.
        $semuaSiswa = Siswa::all();
        $hasilKemiripan = [];
        foreach ($tidakDitemukan as $dataPip) {
            $namaBersih = $this->bersihkanNama($dataPip['nama_pd'] ?? '');
            $terbaik = null;
            $skorTerbaik = 0;
            foreach ($semuaSiswa as $s) {
                similar_text($namaBersih, $this->bersihkanNama($s->nama_lengkap), $persen);
                if ($persen > $skorTerbaik) {
                    $skorTerbaik = $persen;
                    $terbaik = $s;
                }
            }

            $hasilKemiripan[] = [
                'dataPip' => $dataPip,
                'idSiswaTebakan' => $skorTerbaik >= 60 ? $terbaik?->id_member : null,
                'skor' => round($skorTerbaik, 1),
            ];
        }

        usort($hasilKemiripan, fn ($a, $b) => $b['skor'] <=> $a['skor']);

        return view('superadmin.pip.kemiripan', [
            'hasilKemiripan' => $hasilKemiripan,
            'semuaSiswa' => $semuaSiswa,
            'langsungTersimpan' => $langsungTersimpan,
        ]);
    }

    /** Step 2: konfirmasi hasil kemiripan (yang dicentang & dipilih siswanya) baru benar-benar disimpan. */
    public function simpanKemiripan(Request $request)
    {
        $data = $request->validate(['konfirmasi' => ['nullable', 'array']]);

        $berhasil = 0;
        foreach ($data['konfirmasi'] ?? [] as $item) {
            $idSiswa = $item['id_siswa'] ?? null;
            $nisn = $item['nisn'] ?? null;
            if (!$idSiswa || !$nisn) {
                continue;
            }

            $dataPip = json_decode($item['data_pip'], true);
            PipSiswa::updateOrCreate(['nisn' => $nisn], array_merge($dataPip, ['id_siswa' => $idSiswa]));
            $berhasil++;
        }

        return redirect()->route('superadmin.pip.form')->with('status', "{$berhasil} data tambahan berhasil dikonfirmasi & disimpan.");
    }

    private function ekstrakDataPip(array $r, array $kolom): array
    {
        $ambil = fn ($nama) => isset($kolom[$nama]) ? ($r[$kolom[$nama]] ?? null) : null;
        $bersihkanTeks = fn ($v) => $v !== null ? ltrim(trim((string) $v), "'") : null;
        $bersihkanTanggal = function ($v) {
            if (!$v) {
                return null;
            }
            try {
                return \Carbon\Carbon::parse($v)->toDateString();
            } catch (\Throwable $e) {
                return null;
            }
        };

        return [
            'nama_pd' => $bersihkanTeks($ambil('nama_pd')),
            'kelas_excel' => $bersihkanTeks($ambil('kelas')),
            'nominal' => $ambil('nominal'),
            'no_rekening' => $bersihkanTeks($ambil('no_rekening')),
            'tahap_id' => $bersihkanTeks($ambil('tahap_id')),
            'nomor_sk' => $bersihkanTeks($ambil('nomor_sk')),
            'tanggal_sk' => $bersihkanTanggal($ambil('tanggal_sk')),
            'nama_rekening' => $bersihkanTeks($ambil('nama_rekening')),
            'tanggal_cair' => $bersihkanTanggal($ambil('tanggal_cair')),
            'status_cair' => $bersihkanTeks($ambil('status_cair')),
            'no_kip' => $bersihkanTeks($ambil('no_KIP')),
            'no_kks' => $bersihkanTeks($ambil('no_KKS')),
            'no_kps' => $bersihkanTeks($ambil('no_KPS')),
            'virtual_acc' => $bersihkanTeks($ambil('virtual_acc')),
            'nama_kartu' => $bersihkanTeks($ambil('nama_kartu')),
            'semester_id' => $bersihkanTeks($ambil('semester_id')),
            'layak_pip' => $bersihkanTeks($ambil('layak_pip')),
            'keterangan_pencairan' => $bersihkanTeks($ambil('keterangan_pencairan')),
            'confirmation_text' => $bersihkanTeks($ambil('confirmation_text')),
            'tahap_keterangan' => $bersihkanTeks($ambil('tahap_keterangan')),
            'nama_pengusul' => $bersihkanTeks($ambil('nama_pengusul')),
        ];
    }

    private function bersihkanNama(string $teks): string
    {
        $teks = strtolower($teks);
        $teks = preg_replace('/[^a-z\s]/', ' ', $teks);

        return trim(preg_replace('/\s+/', ' ', $teks));
    }
}
