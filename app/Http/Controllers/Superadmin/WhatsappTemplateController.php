<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\WhatsappTemplate;
use Illuminate\Http\Request;

class WhatsappTemplateController extends Controller
{
    /**
     * Edit-only - kode template sudah tetap (dipakai literal di
     * WhatsappWebhookController), cuma isi teksnya yang boleh diubah.
     * Tidak ada tambah/hapus di sini.
     */
    public function index()
    {
        $template = WhatsappTemplate::orderBy('kode')->get();

        return view('superadmin.whatsapp-template.index', compact('template'));
    }

    public function edit(WhatsappTemplate $whatsappTemplate)
    {
        return view('superadmin.whatsapp-template.form', ['item' => $whatsappTemplate]);
    }

    public function update(Request $request, WhatsappTemplate $whatsappTemplate)
    {
        $data = $request->validate([
            'teks' => ['required', 'string'],
        ]);

        $whatsappTemplate->update($data);

        return redirect()->route('superadmin.whatsapp-template.index')->with('status', 'Teks balasan bot berhasil diperbarui.');
    }
}
