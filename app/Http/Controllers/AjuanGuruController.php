<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Member;
use App\Models\Warning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanGuruController extends Controller
{
    const KATEGORI = 'Kelas Kosong';

    /** Pengganti menu "Ajukan Guru" kepsek - form lapor kelas tidak ada yang mengajar. */
    public function form(Request $request)
    {
        $cari = trim((string) $request->input('cari'));
        $guru = null;

        if ($cari !== '') {
            $guru = Guru::where('nama', 'like', '%'.$cari.'%')->orderBy('nama')->limit(20)->get();
        }

        return view('ajuan-guru.form', ['guru' => $guru, 'cari' => $cari]);
    }

    /** Simpan laporan kelas kosong - masuk ke tabel warning, kategori "Kelas Kosong", butuh konfirmasi guru. */
    public function simpan(Request $request, Guru $guru)
    {
        $data = $request->validate([
            'kelas' => ['required', 'string', 'max:20'],
            'jam' => ['nullable', 'integer'],
            'keterangan' => ['required', 'string', 'max:255'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        Warning::create([
            'id_guru' => $guru->id_guru,
            'tgl_warning' => Carbon::today()->toDateString(),
            'kategori' => self::KATEGORI,
            'keterangan' => $data['keterangan'],
            'jam' => $data['jam'] ?? null,
            'kelas' => $data['kelas'],
            'id_entry' => $member->id,
            'aksi' => null, // null/kosong = menunggu konfirmasi alasan dari guru
        ]);

        return redirect()->route('ajuan-guru.list')->with('status', 'Laporan kelas kosong untuk '.$guru->nama.' berhasil dikirim, menunggu konfirmasi.');
    }

    /** Pengganti "List Ajuan Guru" kepsek - lihat status semua laporan kelas kosong. */
    public function list(Request $request)
    {
        $laporan = Warning::with(['guru', 'pelapor'])
            ->where('kategori', self::KATEGORI)
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('ajuan-guru.list', compact('laporan'));
    }

    /**
     * Guru memberi alasan/konfirmasi atas laporan kelas kosong yang ditujukan
     * ke dirinya - ini yang membedakan warning kategori ini dari warning
     * otomatis (Alpha/Sering Tidak Masuk) yang sekadar informasi.
     */
    public function konfirmasi(Request $request, Warning $warning)
    {
        $data = $request->validate([
            'alasan' => ['required', 'string', 'max:255'],
        ]);

        $warning->update(['aksi' => $data['alasan']]);

        return back()->with('status', 'Konfirmasi alasan berhasil dikirim.');
    }
}
