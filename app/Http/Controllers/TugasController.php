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

        return view('tugas.upload', compact('guru', 'kelas', 'tugas', 'tanggal'));
    }

    /** Pengganti prosestugas.php - simpan tugas untuk kelas & tanggal tertentu. */
    public function simpan(Request $request, Guru $guru, string $kelas)
    {
        $data = $request->validate([
            'tugas' => ['required', 'string', 'max:255'],
            'keterangan' => ['nullable', 'string', 'max:255'],
            'foto' => ['nullable', 'image', 'max:8192'],
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
            $atribut['gambar'] = $request->file('foto')->store('tugas', 'public');
        }

        Tugas::updateOrCreate(
            ['idguru' => $guru->id_guru, 'kelas' => $kelas, 'tgl_tugas' => $tanggal],
            $atribut
        );

        $kembali = $request->boolean('dari_ajuan_sendiri')
            ? route('ajuan-absen-guru.index', ['tanggal' => $tanggal])
            : route('jadwal.guru', $guru);

        return redirect($kembali)->with('status', 'Tugas untuk kelas '.$kelas.' berhasil diupload.');
    }
}
