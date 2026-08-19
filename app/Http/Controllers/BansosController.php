<?php

namespace App\Http\Controllers;

use App\Models\BansosAjuan;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BansosController extends Controller
{
    const MAKS_PER_KELAS = 7;
    const JUMLAH_UTAMA = 5; // 5 pertama hijau, 2 terakhir (6-7) kuning

    /** Halaman wali kelas - centang max 5 anak di kelasnya untuk diajukan Bansos. */
    public function ajukan()
    {
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);
        abort_if($kelas === '', 403, 'Akun ini tidak terhubung ke kelas manapun sebagai wali kelas.');

        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap')->get();
        $idSudahDiajukan = BansosAjuan::where('kelas', $kelas)->orderBy('id')->pluck('id_siswa')->toArray();

        return view('bansos.ajukan', compact('kelas', 'siswa', 'idSudahDiajukan'));
    }

    public function simpanAjuan(Request $request)
    {
        $member = Auth::guard('member')->user();
        $kelas = trim((string) $member->walikelas);
        abort_if($kelas === '', 403, 'Akun ini tidak terhubung ke kelas manapun sebagai wali kelas.');

        $data = $request->validate([
            'siswa' => ['nullable', 'array', 'max:'.self::MAKS_PER_KELAS],
            'siswa.*' => ['integer'],
        ]);

        $idDipilih = $data['siswa'] ?? [];

        // Validasi siswa yang dipilih memang anak kelas ini, TAPI tetap jaga urutan
        // klik dari user (biar 5 pertama/2 terakhir konsisten sesuai yang diklik).
        $idAnakKelasIni = Siswa::where('kelas', $kelas)->pluck('id_member')->toArray();
        $idValid = array_values(array_filter($idDipilih, fn ($id) => in_array($id, $idAnakKelasIni)));

        // Reset dulu ajuan kelas ini, baru isi ulang sesuai pilihan sekarang (maks 5 tetap dijamin di query di atas).
        BansosAjuan::where('kelas', $kelas)->delete();

        foreach ($idValid as $idSiswa) {
            BansosAjuan::create([
                'id_siswa' => $idSiswa,
                'kelas' => $kelas,
                'diajukan_oleh' => $member->id,
            ]);
        }

        return back()->with('status', 'Ajuan Bansos kelas '.$kelas.' berhasil disimpan ('.count($idValid).' siswa).');
    }

    /** Rekap semua penerima Bansos - khusus Tatib & Kesiswaan. */
    public function rekap(Request $request)
    {
        $rekapPerKelas = BansosAjuan::selectRaw('kelas, count(*) as jumlah')
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get();

        $rekap = BansosAjuan::with('siswa')
            ->when($request->filled('kelas'), fn ($q) => $q->where('kelas', $request->input('kelas')))
            ->orderBy('kelas')
            ->orderBy('id_siswa')
            ->paginate(20)
            ->withQueryString();

        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('bansos.rekap', compact('rekap', 'rekapPerKelas', 'daftarKelas'));
    }

    /** Import Excel (kolom: Nama Siswa, Kelas) - dipakai Tatib/Kesiswaan buat isi massal, bukan lewat klik satu-satu. */
    public function importExcel(Request $request)
    {
        $request->validate(['file_excel' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120']]);

        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($request->file('file_excel')->getRealPath());
        $baris = $spreadsheet->getActiveSheet()->toArray();

        // Baris pertama dianggap header, dilewati. Kolom A = Nama, Kolom B = Kelas.
        $berhasil = 0;
        $tidakDitemukan = [];
        $sudahAda = 0;

        foreach (array_slice($baris, 1) as $r) {
            $nama = trim((string) ($r[0] ?? ''));
            $kelas = trim((string) ($r[1] ?? ''));
            if ($nama === '' || $kelas === '') {
                continue;
            }

            $siswa = Siswa::where('kelas', $kelas)->where('nama_lengkap', 'like', '%'.$nama.'%')->first();

            if (!$siswa) {
                $tidakDitemukan[] = "{$nama} ({$kelas})";

                continue;
            }

            $ada = BansosAjuan::where('id_siswa', $siswa->id_member)->exists();
            if ($ada) {
                $sudahAda++;

                continue;
            }

            BansosAjuan::create([
                'id_siswa' => $siswa->id_member,
                'kelas' => $siswa->kelas,
                'diajukan_oleh' => Auth::guard('member')->id(),
                'keterangan' => 'Import Excel',
            ]);
            $berhasil++;
        }

        $pesan = "{$berhasil} siswa berhasil diimport.";
        if ($sudahAda > 0) {
            $pesan .= " {$sudahAda} dilewati (sudah ada sebelumnya).";
        }
        if (!empty($tidakDitemukan)) {
            $pesan .= ' Tidak ditemukan ('.count($tidakDitemukan).'): '.implode(', ', array_slice($tidakDitemukan, 0, 10)).(count($tidakDitemukan) > 10 ? ', dst.' : '');
        }

        return back()->with('status', $pesan);
    }
}
