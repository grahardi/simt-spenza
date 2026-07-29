<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Pengganti uploadtugas.php - form upload tugas untuk 1 kelas.
     * ?tanggal= opsional (dari alur Ajukan Absen Diri yang bisa untuk
     * tanggal lain, bukan cuma hari ini) - default hari ini kalau kosong.
     * Kalau tugas untuk tanggal itu sudah ada, tampil terisi (bisa diedit).
     */
    public function upload(Request $request, Guru $guru, string $kelas)
    {
        $tanggal = $request->input('tanggal') ? Carbon::parse($request->input('tanggal')) : Carbon::today('Asia/Jakarta');

        $tugas = Tugas::where('idguru', $guru->id_guru)
            ->where('kelas', $kelas)
            ->whereDate('tgl_tugas', $tanggal)
            ->first();

        // Cuma guru yang bersangkutan sendiri yang boleh upload/edit tugasnya.
        // Piket/TU/dll yang buka halaman ini (misal dari Absen Guru atau Ajuan
        // Piket Guru) cuma boleh LIHAT/DOWNLOAD tugas yang sudah diupload.
        $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
        $bisaEdit = $member->dataGuru && $member->dataGuru->id_guru === $guru->id_guru;

        return view('tugas.upload', compact('guru', 'kelas', 'tugas', 'tanggal', 'bisaEdit'));
    }

    /** Pengganti prosestugas.php - simpan tugas untuk kelas & tanggal tertentu. */
    public function simpan(Request $request, Guru $guru, string $kelas)
    {
        $member = \Illuminate\Support\Facades\Auth::guard('member')->user();
        abort_unless($member->dataGuru && $member->dataGuru->id_guru === $guru->id_guru, 403, 'Cuma guru yang bersangkutan sendiri yang bisa upload tugas.');

        $data = $request->validate([
            'tugas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'file', 'mimes:jpg,jpeg,png,gif,webp,doc,docx,pdf', 'max:8192'],
            'tanggal' => ['nullable', 'date'],
        ]);

        $tanggal = $data['tanggal'] ?? Carbon::today('Asia/Jakarta')->toDateString();

        $atribut = [
            'kelas' => $kelas,
            'tgl_tugas' => $tanggal,
            'idguru' => $guru->id_guru,
            'tugas' => $data['tugas'],
            'keterangan' => $data['keterangan'] ?? null,
            'setuju' => 1,
        ];

        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $ekstensi = strtolower($file->getClientOriginalExtension());

            if (in_array($ekstensi, ['doc', 'docx'], true)) {
                $atribut['gambar'] = $this->simpanSebagaiPdf($file);
            } else {
                $atribut['gambar'] = $file->store('tugas', 'public');
            }
        }

        Tugas::updateOrCreate(
            ['idguru' => $guru->id_guru, 'kelas' => $kelas, 'tgl_tugas' => $tanggal],
            $atribut
        );

        $kembali = match (true) {
            $request->boolean('dari_piket') => route('ajuan-absen-guru.piket.form', ['guru' => $guru, 'tanggal' => $tanggal]),
            $request->boolean('dari_ajuan_sendiri') => route('ajuan-absen-guru.index', ['tanggal' => $tanggal]),
            default => route('jadwal.guru', $guru),
        };

        return redirect($kembali)->with('status', 'Tugas untuk kelas '.$kelas.' berhasil diupload.');
    }

    /**
     * Simpan file Word (.doc/.docx) sebagai PDF (dikonversi otomatis pakai
     * LibreOffice headless). Kalau LibreOffice tidak tersedia/gagal, fallback
     * simpan file Word aslinya apa adanya - upload tidak boleh gagal cuma
     * gara-gara konversi gagal.
     */
    private function simpanSebagaiPdf($file): string
    {
        $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $namaAman = \Illuminate\Support\Str::slug($namaAsli).'-'.uniqid();
        $folderKerja = storage_path('app/tmp-konversi-tugas');
        \Illuminate\Support\Facades\File::ensureDirectoryExists($folderKerja);

        $pathAsli = $folderKerja.'/'.$namaAman.'.'.$file->getClientOriginalExtension();
        $file->move($folderKerja, basename($pathAsli));

        $pathPdfHasil = $folderKerja.'/'.$namaAman.'.pdf';

        $perintah = 'timeout 60 libreoffice --headless --convert-to pdf --outdir '
            .escapeshellarg($folderKerja).' '.escapeshellarg($pathAsli).' 2>&1';

        // Beberapa hosting menonaktifkan exec()/shell_exec() demi keamanan -
        // kalau begitu, jangan sampai error fatal, langsung fallback simpan
        // file Word aslinya saja (upload tetap berhasil, cuma tidak dikonversi).
        $kodeKeluar = 1;
        if (function_exists('exec') && !in_array('exec', array_map('trim', explode(',', (string) ini_get('disable_functions'))), true)) {
            exec($perintah, $output, $kodeKeluar);
        }

        if ($kodeKeluar === 0 && file_exists($pathPdfHasil)) {
            $tujuanRelatif = 'tugas/'.$namaAman.'.pdf';
            \Illuminate\Support\Facades\Storage::disk('public')->put(
                $tujuanRelatif,
                file_get_contents($pathPdfHasil)
            );
            @unlink($pathAsli);
            @unlink($pathPdfHasil);

            return $tujuanRelatif;
        }

        // Konversi gagal - simpan file Word aslinya saja, jangan gagalkan upload.
        $tujuanRelatif = 'tugas/'.$namaAman.'.'.$file->getClientOriginalExtension();
        \Illuminate\Support\Facades\Storage::disk('public')->put(
            $tujuanRelatif,
            file_get_contents($pathAsli)
        );
        @unlink($pathAsli);

        return $tujuanRelatif;
    }
}
