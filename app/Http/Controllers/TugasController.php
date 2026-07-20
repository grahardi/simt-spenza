<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Tugas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /** Pengganti uploadtugas.php - form upload tugas untuk 1 kelas (guru sedang/akan absen). */
    public function upload(Guru $guru, string $kelas)
    {
        return view('tugas.upload', compact('guru', 'kelas'));
    }

    /** Pengganti prosestugas.php - simpan tugas untuk kelas hari ini. */
    public function simpan(Request $request, Guru $guru, string $kelas)
    {
        $data = $request->validate([
            'tugas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:8192'],
        ]);

        $atribut = [
            'kelas' => $kelas,
            'tgl_tugas' => Carbon::today()->toDateString(),
            'idguru' => $guru->id_guru,
            'tugas' => $data['tugas'],
            'keterangan' => $data['keterangan'] ?? null,
            'setuju' => 1,
        ];

        if ($request->hasFile('foto')) {
            $atribut['gambar'] = $request->file('foto')->store('tugas', 'public');
        }

        Tugas::updateOrCreate(
            ['idguru' => $guru->id_guru, 'kelas' => $kelas, 'tgl_tugas' => Carbon::today()->toDateString()],
            $atribut
        );

        return redirect()->route('jadwal.guru', $guru)->with('status', 'Tugas untuk kelas '.$kelas.' berhasil diupload.');
    }
}
