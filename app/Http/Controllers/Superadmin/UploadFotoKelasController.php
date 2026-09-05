<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFotoKelasController extends Controller
{
    /** Folder foto per tingkat - tahun masuk (angkatan). Kelas 8/9 sudah ada fotonya, fokus sekarang di kelas 7. */
    const FOLDER_PER_TINGKAT = [
        '7' => 'foto2026',
        '8' => 'foto2025',
        '9' => 'foto2024',
    ];

    public function form()
    {
        $daftarKelas = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');

        return view('superadmin.upload-foto-kelas.form', compact('daftarKelas'));
    }

    /**
     * Step 1: upload banyak foto sekaligus, cocokkan otomatis ke nama siswa
     * di kelas itu berdasarkan KEMIRIPAN NAMA (nama file vs nama_lengkap),
     * simpan sementara, tampilkan halaman preview buat dikonfirmasi.
     */
    public function unggah(Request $request)
    {
        $request->validate([
            'kelas' => ['required', 'string'],
            'foto' => ['required', 'array', 'min:1'],
            'foto.*' => ['image', 'max:8192'],
        ]);

        $kelas = $request->input('kelas');
        $siswaKelas = Siswa::where('kelas', $kelas)->get();

        $sesiId = (string) Str::uuid();
        $folderSementara = 'tmp-upload-foto/'.$sesiId;

        $hasil = [];
        foreach ($request->file('foto') as $file) {
            $namaAsli = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $namaBersih = $this->bersihkanNamaFile($namaAsli);

            // Cari siswa yang namanya paling mirip dengan nama file ini.
            $terbaik = null;
            $skorTerbaik = 0;
            foreach ($siswaKelas as $s) {
                similar_text($namaBersih, $this->bersihkanNamaFile($s->nama_lengkap), $persen);
                if ($persen > $skorTerbaik) {
                    $skorTerbaik = $persen;
                    $terbaik = $s;
                }
            }

            $pathSementara = $file->store($folderSementara, 'public');

            $hasil[] = [
                'path_sementara' => $pathSementara,
                'nama_file_asli' => $file->getClientOriginalName(),
                'id_siswa_tebakan' => $terbaik?->id_member,
                'skor' => round($skorTerbaik, 1),
            ];
        }

        // Urutkan dari yang paling mirip (skor tertinggi) dulu.
        usort($hasil, fn ($a, $b) => $b['skor'] <=> $a['skor']);

        return view('superadmin.upload-foto-kelas.preview', [
            'kelas' => $kelas,
            'hasil' => $hasil,
            'siswaKelas' => $siswaKelas,
            'sesiId' => $sesiId,
        ]);
    }

    /**
     * Step 2: yang tercentang & dikonfirmasi - rename jadi {id_member}.jpg,
     * pindah ke folder final sesuai tingkat, update foto_profil di database.
     */
    public function simpanKonfirmasi(Request $request)
    {
        $data = $request->validate([
            'kelas' => ['required', 'string'],
            'sesi_id' => ['required', 'string'],
            'konfirmasi' => ['nullable', 'array'],
        ]);

        $tingkat = trim(explode('-', $data['kelas'])[0] ?? '');
        $folderTujuan = self::FOLDER_PER_TINGKAT[$tingkat] ?? null;

        if (!$folderTujuan) {
            return back()->with('status_gagal', 'Tingkat kelas "'.$tingkat.'" belum ada folder foto yang dipetakan.');
        }

        $berhasil = 0;
        foreach ($data['konfirmasi'] ?? [] as $item) {
            $pathSementara = $item['path_sementara'] ?? null;
            $idSiswa = $item['id_siswa'] ?? null;

            if (!$pathSementara || !$idSiswa || !Storage::disk('public')->exists($pathSementara)) {
                continue;
            }

            $siswa = Siswa::find($idSiswa);
            if (!$siswa) {
                continue;
            }

            $namaFileFinal = $siswa->id_member.'.jpg';
            $pathFinal = $folderTujuan.'/'.$namaFileFinal;

            // Hapus foto lama di folder ini kalau ada, baru simpan yang baru.
            Storage::disk('public')->delete($pathFinal);
            Storage::disk('public')->move($pathSementara, $pathFinal);

            $siswa->update(['foto_profil' => $pathFinal]);
            $berhasil++;
        }

        // Bersihkan folder sementara sesi ini (sisa foto yang tidak dikonfirmasi).
        Storage::disk('public')->deleteDirectory('tmp-upload-foto/'.$data['sesi_id']);

        return redirect()->route('foto-siswa.kelas', $data['kelas'])
            ->with('status', $berhasil.' foto berhasil disimpan untuk kelas '.$data['kelas'].'.');
    }

    /** Normalisasi nama buat dibandingkan - huruf kecil semua, hilangkan spasi/simbol berlebih. */
    private function bersihkanNamaFile(string $teks): string
    {
        $teks = strtolower($teks);
        $teks = preg_replace('/[^a-z\s]/', ' ', $teks);

        return trim(preg_replace('/\s+/', ' ', $teks));
    }
}
