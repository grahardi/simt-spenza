<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanFitur;
use Illuminate\Http\Request;

class PengaturanFiturController extends Controller
{
    /** Halaman utama - kartu per role (Guru, Piket, Wali Kelas, dst). */
    public function index()
    {
        return view('superadmin.pengaturan-fitur.index');
    }

    /** Detail 1 role - checklist tiap fitur/menunya, centang = aktif. */
    public function detail(string $role)
    {
        return view('superadmin.pengaturan-fitur.detail', compact('role'));
    }

    public function simpan(Request $request, string $role)
    {
        $data = $request->validate(['fitur' => ['nullable', 'array']]);
        $semuaKey = $request->input('semua_fitur_key', []);
        $terpilih = $data['fitur'] ?? [];

        foreach ($semuaKey as $key) {
            PengaturanFitur::updateOrCreate(
                ['role' => $role, 'fitur_key' => $key],
                ['aktif' => in_array($key, $terpilih, true)]
            );
        }

        return redirect()->route('superadmin.pengaturan-fitur.detail', $role)->with('status', 'Pengaturan fitur untuk role "'.$role.'" berhasil disimpan.');
    }
}
