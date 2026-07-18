<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappMenu;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class WhatsappMenuController extends Controller
{
    /**
     * Kelola menu bot WhatsApp - item 'bawaan' (registrasi, absen) alurnya
     * terprogram di WhatsappWebhookController dan tidak bisa dihapus (cuma
     * boleh ubah label/urutan/aktif-nonaktif). Item 'info' bebas
     * ditambah/diubah/dihapus, balasannya teks statis biasa.
     */
    public function index()
    {
        $menu = WhatsappMenu::orderBy('urutan')->get();

        return view('superadmin.whatsapp-menu.index', compact('menu'));
    }

    public function create()
    {
        return view('superadmin.whatsapp-menu.form', ['item' => new WhatsappMenu(['tipe' => 'info'])]);
    }

    public function store(Request $request)
    {
        WhatsappMenu::create($this->validated($request));

        return redirect()->route('superadmin.whatsapp-menu.index')->with('status', 'Menu bot berhasil ditambahkan.');
    }

    public function edit(WhatsappMenu $whatsappMenu)
    {
        return view('superadmin.whatsapp-menu.form', ['item' => $whatsappMenu]);
    }

    public function update(Request $request, WhatsappMenu $whatsappMenu)
    {
        if ($whatsappMenu->isBawaan()) {
            $data = $request->validate([
                'label' => ['required', 'string', 'max:150'],
                'urutan' => ['nullable', 'integer', 'min:0'],
            ]);
            $data['aktif'] = $request->boolean('aktif');
        } else {
            $data = $this->validated($request, $whatsappMenu);
        }

        $whatsappMenu->update($data);

        return redirect()->route('superadmin.whatsapp-menu.index')->with('status', 'Menu bot berhasil diperbarui.');
    }

    public function destroy(WhatsappMenu $whatsappMenu)
    {
        if ($whatsappMenu->isBawaan()) {
            return back()->with('status', 'Menu bawaan ("'.$whatsappMenu->kode.'") tidak bisa dihapus - nonaktifkan saja kalau tidak ingin dipakai.');
        }

        $whatsappMenu->delete();

        return redirect()->route('superadmin.whatsapp-menu.index')->with('status', 'Menu bot berhasil dihapus.');
    }

    private function validated(Request $request, ?WhatsappMenu $current = null): array
    {
        $data = $request->validate([
            'kode' => [
                'required', 'string', 'max:30', 'alpha_dash',
                Rule::unique('whatsapp_menu', 'kode')->ignore($current?->id),
            ],
            'label' => ['required', 'string', 'max:150'],
            'balasan' => ['required', 'string'],
            'urutan' => ['nullable', 'integer', 'min:0'],
        ]);

        $data['tipe'] = 'info'; // tambah/edit lewat form selalu 'info' - 'bawaan' cuma via seed
        $data['aktif'] = $request->boolean('aktif');

        return $data;
    }
}
