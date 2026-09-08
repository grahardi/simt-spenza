<?php

namespace App\Http\Controllers;

use App\Models\KartuUjian;
use App\Models\PengaturanDenah;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class KartuUjianController extends Controller
{
    /** Halaman atur judul kartu/meja & tanggal - tampil di Kartu Ujian, Label Meja, dan Denah. */
    public function pengaturanKartu()
    {
        $pengaturan = \App\Models\PengaturanKartuUjian::ambil();

        return view('kartu-ujian.pengaturan-kartu', compact('pengaturan'));
    }

    public function simpanPengaturanKartu(Request $request)
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:150'],
            'tanggal' => ['required', 'date'],
        ]);

        \App\Models\PengaturanKartuUjian::ambil()->update($data);

        return back()->with('status', 'Pengaturan berhasil disimpan.');
    }

    /** Menu utama - link ke import, cetak kartu, label, denah, pengaturan denah. */
    public function index()
    {
        $jumlahSudahDiisi = KartuUjian::count();
        $jumlahSiswa = Siswa::count();
        $daftarRuang = KartuUjian::whereNotNull('ruang')->distinct()->orderBy('ruang')->pluck('ruang');

        return view('kartu-ujian.index', compact('jumlahSudahDiisi', 'jumlahSiswa', 'daftarRuang'));
    }

    /** Halaman import Excel (Nomor Induk, Password, Ruang, No Kursi). */
    public function formImport()
    {
        return view('kartu-ujian.import');
    }

    /** Download template Excel kosong (contoh 2 baris) buat diisi admin. */
    public function templateExcel()
    {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Template Kartu Ujian');

        $sheet->fromArray(['Nomor Induk', 'Password', 'Ruang', 'No Kursi'], null, 'A1');
        $sheet->getStyle('A1:D1')->getFont()->setBold(true);
        $sheet->fromArray([
            ['17927', 'abc123', '1', '1'],
            ['17928', 'def456', '1', '2'],
        ], null, 'A2');

        foreach (range('A', 'D') as $kolom) {
            $sheet->getColumnDimension($kolom)->setAutoSize(true);
        }

        $path = storage_path('app/tmp-export/template-kartu-ujian.xlsx');
        File::ensureDirectoryExists(dirname($path));
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($path);

        return response()->download($path, 'Template_Kartu_Ujian.xlsx')->deleteFileAfterSend(true);
    }

    /** Proses import Excel - cocokkan Nomor Induk ke siswa, isi/update password+ruang+nokursi. */
    public function prosesImport(Request $request)
    {
        $request->validate(['file_excel' => ['required', 'file', 'mimes:xlsx,xls', 'max:5120']]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file_excel')->getRealPath());
        $baris = $spreadsheet->getActiveSheet()->toArray();

        $berhasil = 0;
        $tidakDitemukan = [];

        foreach (array_slice($baris, 1) as $r) {
            $idSiswa = trim((string) ($r[0] ?? ''));
            if ($idSiswa === '') {
                continue;
            }

            $siswa = Siswa::find($idSiswa);
            if (!$siswa) {
                $tidakDitemukan[] = $idSiswa;

                continue;
            }

            KartuUjian::updateOrCreate(
                ['id_siswa' => $idSiswa],
                [
                    'password' => trim((string) ($r[1] ?? '')) ?: null,
                    'ruang' => trim((string) ($r[2] ?? '')) ?: null,
                    'nokursi' => trim((string) ($r[3] ?? '')) ?: null,
                ]
            );
            $berhasil++;
        }

        $pesan = "{$berhasil} data berhasil diimport/diperbarui.";
        if (!empty($tidakDitemukan)) {
            $pesan .= ' Nomor Induk tidak ditemukan ('.count($tidakDitemukan).'): '.implode(', ', array_slice($tidakDitemukan, 0, 10)).(count($tidakDitemukan) > 10 ? ', dst.' : '');
        }

        return back()->with('status', $pesan);
    }

    /** Data siswa lengkap (gabungan Siswa + KartuUjian) - dipakai bersama oleh cetak kartu & label. */
    private function dataPeserta()
    {
        return KartuUjian::with('siswa')
            ->whereHas('siswa')
            ->get()
            ->sortBy([
                fn ($a, $b) => (int) $a->ruang <=> (int) $b->ruang,
                fn ($a, $b) => (int) $a->siswa->id_member <=> (int) $b->siswa->id_member,
            ]);
    }

    /** Fitur 1: Cetak Kartu Ujian - 8 kartu per lembar F4, dengan password. */
    public function cetakKartu()
    {
        return view('kartu-ujian.cetak-kartu', ['peserta' => $this->dataPeserta()]);
    }

    /** Fitur 2: Label Meja - sama seperti kartu tapi TANPA password. */
    public function cetakLabel()
    {
        return view('kartu-ujian.cetak-label', ['peserta' => $this->dataPeserta()]);
    }

    /** Fitur 3: Denah 1 ruang - grid zigzag, 1 meja bisa isi 2 siswa (nokursi sama). */
    public function denah(string $ruang)
    {
        $tipeDenah = PengaturanDenah::where('ruang', $ruang)->value('tipe') ?? 'kiri';

        $peserta = KartuUjian::with('siswa')
            ->where('ruang', $ruang)
            ->whereHas('siswa')
            ->get()
            ->sortBy(fn ($p) => (int) $p->siswa->id_member)
            ->values();

        $hasilGrid = $this->buatGridZigzag($peserta, $tipeDenah);
        $gridDenah = $hasilGrid['grid'];
        $kelompokMeja = $hasilGrid['kelompokMeja'];

        return view('kartu-ujian.denah', compact('ruang', 'tipeDenah', 'peserta', 'gridDenah', 'kelompokMeja'));
    }

    /** Fitur 4: Cetak semua denah ruang sekaligus (1 halaman per ruang). */
    public function cetakSemuaDenah()
    {
        $daftarRuang = KartuUjian::whereNotNull('ruang')->distinct()->orderByRaw('CAST(ruang AS UNSIGNED) ASC')->pluck('ruang');

        $semuaRuang = $daftarRuang->map(function ($ruang) {
            $tipeDenah = PengaturanDenah::where('ruang', $ruang)->value('tipe') ?? 'kiri';
            $peserta = KartuUjian::with('siswa')
                ->where('ruang', $ruang)
                ->whereHas('siswa')
                ->get()
                ->sortBy(fn ($p) => (int) $p->siswa->id_member)
                ->values();

            $hasilGrid = $this->buatGridZigzag($peserta, $tipeDenah);

            return (object) [
                'ruang' => $ruang,
                'tipeDenah' => $tipeDenah,
                'peserta' => $peserta,
                'gridDenah' => $hasilGrid['grid'],
                'kelompokMeja' => $hasilGrid['kelompokMeja'],
            ];
        });

        return view('kartu-ujian.cetak-semua-denah', compact('semuaRuang'));
    }

    /** Halaman atur tipe denah (kiri/kanan) per ruang. */
    public function pengaturanDenah()
    {
        $daftarRuang = KartuUjian::whereNotNull('ruang')->distinct()->orderByRaw('CAST(ruang AS UNSIGNED) ASC')->pluck('ruang');
        $pengaturan = PengaturanDenah::whereIn('ruang', $daftarRuang)->get()->keyBy('ruang');

        return view('kartu-ujian.pengaturan-denah', compact('daftarRuang', 'pengaturan'));
    }

    public function simpanPengaturanDenah(Request $request)
    {
        $data = $request->validate([
            'ruang' => ['required', 'array'],
            'tipe' => ['required', 'array'],
        ]);

        foreach ($data['ruang'] as $i => $ruang) {
            PengaturanDenah::updateOrCreate(['ruang' => $ruang], ['tipe' => $data['tipe'][$i] ?? 'kiri']);
        }

        return back()->with('status', 'Pengaturan tipe denah berhasil disimpan.');
    }

    /** Grid zigzag 4x4 (16 kursi) - sama logikanya dengan skrip PHP asli. */
    /**
     * Grid zigzag - 1 MEJA (kotak grid) berisi SEKELOMPOK siswa yang nokursi-nya
     * SAMA (biasanya 2 siswa berbagi 1 meja fisik). Jadi grid dihitung dari
     * JUMLAH MEJA (nokursi unik), bukan jumlah siswa - tiap sel bisa isi 1-2 kartu.
     */
    private function buatGridZigzag($peserta, string $tipeDenah): array
    {
        // Kelompokkan per nokursi - urutan numerik (nokursi '1','2',...,'10' bukan alfabet).
        $kelompokMeja = $peserta->groupBy('nokursi')
            ->sortBy(fn ($grup, $nokursi) => (int) $nokursi)
            ->values();

        $totalKolom = 4;
        $totalMeja = $kelompokMeja->count();
        $totalBaris = (int) ceil($totalMeja / $totalKolom);
        $grid = [];

        for ($i = 0; $i < $totalBaris; $i++) {
            $barisIndex = [];
            for ($j = 0; $j < $totalKolom; $j++) {
                $index = ($i * $totalKolom) + $j;
                $barisIndex[] = $index < $totalMeja ? $index : null;
            }

            if ($tipeDenah === 'kiri') {
                if ($i % 2 !== 0) {
                    $barisIndex = array_reverse($barisIndex);
                }
            } else {
                if ($i % 2 === 0) {
                    $barisIndex = array_reverse($barisIndex);
                }
            }

            $grid[] = $barisIndex;
        }

        return ['grid' => $grid, 'kelompokMeja' => $kelompokMeja];
    }
}
