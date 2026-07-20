<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Siswa;
use Illuminate\Http\Request;

class FotoSiswaController extends Controller
{
    /** Grid pilih kelas (seperti Ajukan Absensi/Kebersihan), plus pencarian global di atasnya. */
    public function pilihKelas(Request $request)
    {
        $cari = trim((string) $request->input('cari'));

        if ($cari !== '') {
            return $this->hasilCari($request, $cari);
        }

        $daftarKelas = Kelas::orderBy('nama_kelas')->get();

        return view('foto-siswa.pilih-kelas', compact('daftarKelas'));
    }

    /** Pengganti ubahfoto.php - gallery foto 1 kelas + upload/ganti foto per siswa. */
    public function kelas(string $kelas)
    {
        $siswa = Siswa::where('kelas', $kelas)->orderBy('nama_lengkap')->get();

        return view('foto-siswa.gallery', ['siswa' => $siswa, 'judul' => 'Kelas '.$kelas]);
    }

    /** Pencarian foto siswa lintas kelas. */
    private function hasilCari(Request $request, string $cari)
    {
        $siswa = Siswa::query()
            ->where(function ($query) use ($cari) {
                $query->where('nama_lengkap', 'like', '%'.$cari.'%')
                    ->orWhere('id_member', 'like', '%'.$cari.'%');
            })
            ->orderBy('nama_lengkap')
            ->limit(60)
            ->get();

        return view('foto-siswa.gallery', ['siswa' => $siswa, 'judul' => 'Hasil cari "'.$cari.'"', 'cari' => $cari]);
    }

    /**
     * Pengganti bagian upload di ubahfoto.php - upload/ganti foto 1 siswa.
     * Beda dari lama: nama file & folder konsisten dengan sistem baru
     * (storage/app/public/siswa/, nama file = kolom foto_profil), dan
     * kolom foto_profil di database ikut ter-update (dulu tidak disentuh
     * sama sekali - hanya mengandalkan konvensi nama file id_member.jpg).
     */
    public function upload(Request $request, Siswa $siswa)
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpg,jpeg', 'max:8192'],
        ]);

        // Hapus foto lama kalau ada, supaya tidak menumpuk file tak terpakai.
        if ($siswa->foto_profil) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete('siswa/'.$siswa->foto_profil);
        }

        $namaFile = $siswa->id_member.'.jpg';
        $request->file('foto')->storeAs('siswa', $namaFile, 'public');

        $siswa->update(['foto_profil' => $namaFile]);

        return back()->with('status', 'Foto '.$siswa->nama_lengkap.' berhasil diperbarui.');
    }
}
