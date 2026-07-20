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
            'dasar' => ['required', 'string', 'max:500'],
            'hari' => ['required', 'string', 'max:30'],
            'tanggal' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date'],
            'jam_mulai' => ['required', 'string', 'max:10'],
            'jam_selesai' => ['nullable', 'string', 'max:10'],
            'tempat' => ['required', 'string', 'max:200'],
            'tema' => ['required', 'string', 'max:200'],
            'tempat_tujuan' => ['required', 'string', 'max:200'],
            'maksud' => ['required', 'string', 'max:300'],
            'total_hari' => ['nullable', 'integer', 'min:1'],
        ]);

        $data['total_hari'] = $data['total_hari'] ?? 1;

        AjuanSurat::create([
            'id_guru' => $guru->id_guru,
            'jenis_surat' => 'sppd',
            'data' => $data,
            'status' => 'menunggu',
        ]);

        return redirect()->route('ajuan-surat.index')->with('status', 'Ajuan SPPD berhasil dikirim, menunggu diproses Tata Usaha.');
    }
}
