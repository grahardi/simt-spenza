<?php

namespace App\Http\Controllers;

use App\Models\SoalUpload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SoalUploadController extends Controller
{
    const DAFTAR_MAPEL = [
        'TIK' => 'Informatika', 'IPA' => 'IPA', 'BING' => 'Bahasa Inggris',
        'IPS' => 'IPS', 'PAI' => 'Pend. Agama Islam', 'SENI' => 'Seni Budaya',
        'BIN' => 'Bahasa Indonesia', 'PJOK' => 'PJOK', 'PKN' => 'Pend. Pancasila',
        'BADER' => 'Bahasa Daerah', 'PAK' => 'Pend. Agama Kristen',
        'MAT' => 'Matematika', 'PRAKARYA' => 'Prakarya',
    ];

    /** Form upload - guru pilih kelas + mapel, upload file docx. */
    public function form()
    {
        return view('soal-upload.form', ['daftarMapel' => self::DAFTAR_MAPEL]);
    }

    /**
     * Simpan upload - kalau sudah ada file lama untuk kombinasi kelas+mapel
     * ini, pindahkan dulu ke folder arsip (jangan hilang), baru simpan file
     * baru dengan nama rapi "kelas{N}_{mapel}.docx".
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'kelas' => ['required', 'in:7,8,9'],
            'mapel' => ['required', 'string', 'in:'.implode(',', self::DAFTAR_MAPEL)],
            'file_soal' => ['required', 'file', 'mimes:docx', 'max:20480'],
        ]);

        $mapelSlug = Str::slug($data['mapel'], '');
        $namaFile = 'kelas'.$data['kelas'].'_'.$mapelSlug.'.docx';
        $pathBaru = 'soal/'.$namaFile;

        $existing = SoalUpload::where('kelas', $data['kelas'])->where('mapel', $data['mapel'])->first();

        if ($existing && Storage::disk('public')->exists($existing->path)) {
            // Arsipkan file lama - kasih timestamp biar tidak tertimpa arsip sebelumnya.
            $namaArsip = 'soal/arsip/'.now()->format('Ymd-His').'_'.$namaFile;
            Storage::disk('public')->move($existing->path, $namaArsip);
        }

        $request->file('file_soal')->storeAs('soal', $namaFile, 'public');

        SoalUpload::updateOrCreate(
            ['kelas' => $data['kelas'], 'mapel' => $data['mapel']],
            ['path' => $pathBaru, 'id_guru' => Auth::guard('member')->user()->dataGuru?->id_guru]
        );

        return back()->with('status', 'Soal Kelas '.$data['kelas'].' - '.$data['mapel'].' berhasil diupload.'.($existing ? ' File lama otomatis dipindah ke arsip.' : ''));
    }

    /** Hapus (sebenarnya cuma dipindah ke arsip, tidak benar-benar hilang) - admin bisa semua, guru cuma miliknya sendiri. */
    public function hapus(SoalUpload $soalUpload)
    {
        $member = Auth::guard('member')->user();
        $iniMiliknya = $member->dataGuru && $soalUpload->id_guru === $member->dataGuru->id_guru;

        abort_unless($member->hasRole('adminsoal') || $iniMiliknya, 403, 'Anda tidak berhak menghapus soal ini.');

        if (Storage::disk('public')->exists($soalUpload->path)) {
            $namaArsip = 'soal/arsip/'.now()->format('Ymd-His').'_'.basename($soalUpload->path);
            Storage::disk('public')->move($soalUpload->path, $namaArsip);
        }

        $soalUpload->delete();

        return back()->with('status', 'Soal Kelas '.$soalUpload->kelas.' - '.$soalUpload->mapel.' dihapus (file dipindah ke arsip, tidak benar-benar hilang).');
    }

    /** List semua soal yang sudah terupload - khusus Admin Soal. */
    public function index()
    {
        $daftar = SoalUpload::with('guru')->orderBy('kelas')->orderBy('mapel')->get();

        return view('soal-upload.index', ['daftar' => $daftar, 'semua' => true]);
    }

    /** Guru cuma lihat soal yang dia sendiri upload (bukan punya guru lain). */
    public function milikSaya()
    {
        $idGuru = Auth::guard('member')->user()->dataGuru?->id_guru;
        abort_if(!$idGuru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        $daftar = SoalUpload::with('guru')->where('id_guru', $idGuru)->orderBy('kelas')->orderBy('mapel')->get();

        return view('soal-upload.index', ['daftar' => $daftar, 'semua' => false]);
    }

    /** Download semua soal sekaligus dalam 1 file ZIP - khusus Admin Soal. */
    public function downloadSemua()
    {
        $daftar = SoalUpload::all();
        if ($daftar->isEmpty()) {
            return back()->with('status_gagal', 'Belum ada soal yang terupload.');
        }

        $namaZip = 'soal-semua-'.now()->format('Ymd-His').'.zip';
        $pathZip = storage_path('app/tmp-zip/'.$namaZip);
        \Illuminate\Support\Facades\File::ensureDirectoryExists(dirname($pathZip));

        $zip = new \ZipArchive();
        $zip->open($pathZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($daftar as $soal) {
            $pathAsli = Storage::disk('public')->path($soal->path);
            if (file_exists($pathAsli)) {
                $zip->addFile($pathAsli, basename($soal->path));
            }
        }
        $zip->close();

        return response()->download($pathZip, $namaZip)->deleteFileAfterSend(true);
    }
}
