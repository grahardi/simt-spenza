<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Guru;
use App\Models\Siswa;
use Illuminate\Http\Request;

class GuruWaliController extends Controller
{
    public function index(Request $request)
    {
        $query = Siswa::with('guruWali')
            ->when($request->filled('cari'), fn ($q) => $q->where('nama_lengkap', 'like', '%'.$request->input('cari').'%'))
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->input('kelas')))
            ->when($request->filled('id_guru_wali'), fn ($q) => $q->where('id_guru_wali', $request->input('id_guru_wali')))
            ->when($request->input('status') === 'belum', fn ($q) => $q->whereNull('id_guru_wali'));

        // Urut berdasarkan nama guru wali (siswa tanpa wali ditaruh di akhir),
        // baru diikutkan urut kelas & nama sebagai pengurut kedua.
        $query->leftJoin('guru', 'datasiswa.id_guru_wali', '=', 'guru.id_guru')
            ->orderByRaw('guru.nama IS NULL')
            ->orderBy('guru.nama')
            ->orderBy('datasiswa.kelas')
            ->orderBy('datasiswa.nama_lengkap')
            ->select('datasiswa.*');

        $siswa = $query->paginate(30)->withQueryString();

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('superadmin.guru-wali.index', compact('siswa', 'daftarKelas', 'daftarGuru'));
    }

    /** Halaman terpisah - rekap jumlah siswa per guru wali. */
    public function rekap()
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        $rekapJumlah = Siswa::whereNotNull('id_guru_wali')
            ->selectRaw('id_guru_wali, count(*) as jumlah')
            ->groupBy('id_guru_wali')
            ->get()
            ->map(function ($row) use ($daftarGuru) {
                $row->guru = $daftarGuru->firstWhere('id_guru', $row->id_guru_wali);
                return $row;
            })
            ->sortBy(fn ($r) => $r->guru->nama ?? '')
            ->values();

        return view('superadmin.guru-wali.rekap', compact('rekapJumlah'));
    }

    /** Export Excel - list semua siswa dikelompokkan per guru wali, format sama seperti contoh referensi. */
    public function exportExcel()
    {
        $siswa = Siswa::with('guruWali')
            ->whereNotNull('id_guru_wali')
            ->get()
            ->sortBy(function ($s) {
                // Urutan alami kelas: 7-A, 7-B, ... 9-J (bukan abjad biasa yang
                // salah urut jadi 7-A, 8-A, 9-A, 7-B, dst)
                preg_match('/(\d+)\s*-\s*([A-Za-z]+)/', $s->kelas, $m);
                $angka = $m[1] ?? 0;
                $huruf = $m[2] ?? 'Z';
                return sprintf('%02d-%s', $angka, $huruf);
            })
            ->values();

        // Sub-urut per kelas berdasarkan Nomor Induk (id_member) - standar list sistem
        $siswaPerKelas = $siswa->groupBy('kelas')->map(fn ($grup) => $grup->sortBy('id_member')->values());

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['NO.', 'KELAS', 'NO', 'NOMOR INDUK', 'NAMA SISWA', 'GURU WALI'], null, 'A1');
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $baris = 2;
        $noKelas = 0;

        foreach ($siswaPerKelas as $kelas => $grup) {
            $noKelas++;
            $baseRow = $baris;

            foreach ($grup as $i => $s) {
                $sheet->setCellValue('C'.$baris, $i + 1);
                $sheet->setCellValue('D'.$baris, $s->id_member);
                $sheet->setCellValue('E'.$baris, strtoupper($s->nama_lengkap));
                $sheet->setCellValue('F'.$baris, $s->guruWali->nama ?? '-');
                $baris++;
            }

            $barisAkhir = $baris - 1;

            $sheet->setCellValue('A'.$baseRow, $noKelas);
            $sheet->setCellValue('B'.$baseRow, str_replace(' - ', '-', $kelas));

            if ($barisAkhir > $baseRow) {
                $sheet->mergeCells('A'.$baseRow.':A'.$barisAkhir);
                $sheet->mergeCells('B'.$baseRow.':B'.$barisAkhir);
            }
            $sheet->getStyle('A'.$baseRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('B'.$baseRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
        }

        foreach (['A' => 6, 'B' => 12, 'C' => 6, 'D' => 14, 'E' => 40, 'F' => 30] as $kolom => $lebar) {
            $sheet->getColumnDimension($kolom)->setWidth($lebar);
        }

        $namaFile = 'Rekap_Guru_Wali_'.now()->format('Ymd').'.xlsx';
        $path = storage_path('app/public/'.$namaFile);
        (new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet))->save($path);

        return response()->download($path)->deleteFileAfterSend(true);
    }

    /** Assign guru wali ke banyak siswa sekaligus (checkbox + pilih guru). */
    public function assign(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', 'array', 'min:1'],
            'siswa_id.*' => ['integer'],
            'id_guru' => ['required', 'integer', 'exists:guru,id_guru'],
        ]);

        Siswa::whereIn('id_member', $data['siswa_id'])->update(['id_guru_wali' => $data['id_guru']]);

        $guru = Guru::find($data['id_guru']);

        return back()->with('status', count($data['siswa_id']).' siswa berhasil di-assign ke wali '.($guru->nama ?? '-').'.');
    }

    /** Lepas wali dari 1 siswa. */
    public function lepas(Siswa $siswa)
    {
        $siswa->update(['id_guru_wali' => null]);

        return back()->with('status', 'Wali '.$siswa->nama_lengkap.' berhasil dilepas.');
    }

    /** Lepas wali dari banyak siswa sekaligus (checkbox). */
    public function lepasMassal(Request $request)
    {
        $data = $request->validate([
            'siswa_id' => ['required', 'array', 'min:1'],
            'siswa_id.*' => ['integer'],
        ]);

        Siswa::whereIn('id_member', $data['siswa_id'])->update(['id_guru_wali' => null]);

        return back()->with('status', count($data['siswa_id']).' siswa berhasil dilepas dari wali.');
    }
}
