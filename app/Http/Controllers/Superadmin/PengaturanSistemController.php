<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\PengaturanSistem;
use Illuminate\Http\Request;

class PengaturanSistemController extends Controller
{
    public function edit()
    {
        $pengaturan = PengaturanSistem::ambil();

        return view('superadmin.pengaturan-sistem.edit', compact('pengaturan'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'nomor_wa_kepsek' => ['nullable', 'string', 'max:20'],
        ]);

        PengaturanSistem::ambil()->update($data);

        return redirect()->route('superadmin.pengaturan-sistem.edit')->with('status', 'Pengaturan sistem berhasil disimpan.');
    }
}
