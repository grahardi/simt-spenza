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

        // Rekap jumlah siswa per guru wali (dari SELURUH data, bukan cuma halaman ini)
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

        return view('superadmin.guru-wali.index', compact('siswa', 'daftarKelas', 'daftarGuru', 'rekapJumlah'));
    }

    /** Export Excel - list semua siswa dikelompokkan per guru wali, format sama seperti contoh referensi. */
    public function exportExcel()
    {
        $siswa = Siswa::with('guruWali')
            ->whereNotNull('id_guru_wali')
            ->join('guru', 'datasiswa.id_guru_wali', '=', 'guru.id_guru')
            ->orderBy('guru.nama')
            ->orderBy('datasiswa.kelas')
            ->orderBy('datasiswa.nama_lengkap')
            ->select('datasiswa.*')
            ->get();

        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->fromArray(['NO.', 'GURU WALI', 'NO', 'NAMA SISWA', 'KELAS'], null, 'A1');
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $baris = 2;
        $noGuru = 0;
        $guruSebelumnya = null;

        foreach ($siswa->groupBy('id_guru_wali') as $grup) {
            $guru = $grup->first()->guruWali;
            $noGuru++;
            $baseRow = $baris;

            foreach ($grup->values() as $i => $s) {
                $sheet->setCellValue('C'.$baris, $i + 1);
                $sheet->setCellValue('D'.$baris, strtoupper($s->nama_lengkap));
                $sheet->setCellValue('E'.$baris, str_replace(' - ', '-', $s->kelas));
                $baris++;
            }

            $barisAkhir = $baris - 1;

            $sheet->setCellValue('A'.$baseRow, $noGuru);
            $sheet->setCellValue('B'.$baseRow, $guru->nama ?? '-');

            if ($barisAkhir > $baseRow) {
                $sheet->mergeCells('A'.$baseRow.':A'.$barisAkhir);
                $sheet->mergeCells('B'.$baseRow.':B'.$barisAkhir);
            }
            $sheet->getStyle('A'.$baseRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP);
            $sheet->getStyle('B'.$baseRow)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP)->setWrapText(true);

            // Baris NIP/NIPPPK di bawah nama guru (baris kedua kelompok, kalau ada)
            if ($guru && $guru->nip && $baseRow + 1 <= $barisAkhir) {
                $labelNip = str_contains(strtolower($guru->status ?? ''), 'pppk') ? 'NIPPPK ' : 'NIP ';
                $sheet->setCellValue('B'.($baseRow + 1), $labelNip.$guru->nip);
            }
        }

        foreach (['A' => 6, 'B' => 30, 'C' => 6, 'D' => 40, 'E' => 10] as $kolom => $lebar) {
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
