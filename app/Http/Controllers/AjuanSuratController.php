<?php

namespace App\Http\Controllers;

use App\Models\AjuanSurat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AjuanSuratController extends Controller
{
    private function guruLogin()
    {
        $member = Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        return $guru;
    }

    /** List ajuan surat milik guru yang login. */
    public function index()
    {
        $guru = $this->guruLogin();

        $daftar = AjuanSurat::where('id_guru', $guru->id_guru)
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('ajuan-surat.index', compact('guru', 'daftar'));
    }

    /** Form Ajukan SPPD. */
    public function createSppd()
    {
        $guru = $this->guruLogin();

        return view('ajuan-surat.form-sppd', compact('guru'));
    }

    public function storeSppd(Request $request)
    {
        $guru = $this->guruLogin();

        $data = $request->validate([
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'jam_mulai' => ['required', 'string', 'max:10'],
            'jam_selesai' => ['nullable', 'string', 'max:10'],
            'tempat_tujuan' => ['required', 'string', 'max:200'],
            'tema' => ['required', 'string', 'max:200'],
            'total_hari' => ['nullable', 'integer', 'min:1'],
            'berkas_pendukung' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:8192'],
        ]);

        // Hari dihitung otomatis dari tanggal berangkat, tidak perlu diisi manual.
        $data['hari'] = \Carbon\Carbon::parse($data['tanggal'])->translatedFormat('l');
        $data['total_hari'] = $data['total_hari'] ?? 1;

        $filePendukung = null;
        if ($request->hasFile('berkas_pendukung')) {
            $filePendukung = $request->file('berkas_pendukung')->store('ajuan-surat/pendukung', 'public');
        }
        unset($data['berkas_pendukung']); // jangan ikut masuk ke kolom JSON `data`

        AjuanSurat::create([
            'id_guru' => $guru->id_guru,
            'jenis_surat' => 'sppd',
            'data' => $data,
            'file_pendukung' => $filePendukung,
            'status' => 'menunggu',
        ]);

        return redirect()->route('ajuan-surat.index')->with('status', 'Ajuan SPPD berhasil dikirim, menunggu diproses Tata Usaha.');
    }
}
