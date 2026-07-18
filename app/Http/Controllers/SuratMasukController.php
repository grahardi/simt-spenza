<?php

namespace App\Http\Controllers;

use App\Models\SuratMasuk;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SuratMasukController extends Controller
{
    public function index(Request $request)
    {
        $surat = SuratMasuk::query()
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where('perihal', 'like', '%'.$cari.'%')
                    ->orWhere('asal_surat', 'like', '%'.$cari.'%')
                    ->orWhere('nomor_surat', 'like', '%'.$cari.'%');
            })
            ->orderByDesc('tanggal_terima')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('persuratan.surat-masuk.index', compact('surat'));
    }

    public function create()
    {
        return view('persuratan.surat-masuk.form', ['item' => new SuratMasuk()]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if ($request->hasFile('file_scan')) {
            $data['file_scan'] = $request->file('file_scan')->store('surat-masuk', 'public');
        }

        $data['dicatat_oleh'] = Auth::guard('member')->id();
        $data['status'] = 'baru';

        SuratMasuk::create($data);

        return redirect()->route('surat-masuk.index')->with('status', 'Surat masuk berhasil dicatat.');
    }

    public function edit(SuratMasuk $suratMasuk)
    {
        return view('persuratan.surat-masuk.form', ['item' => $suratMasuk]);
    }

    public function update(Request $request, SuratMasuk $suratMasuk)
    {
        $data = $this->validated($request);
        $data['status'] = $request->input('status', $suratMasuk->status);

        if ($request->hasFile('file_scan')) {
            $data['file_scan'] = $request->file('file_scan')->store('surat-masuk', 'public');
        }

        $suratMasuk->update($data);

        return redirect()->route('surat-masuk.index')->with('status', 'Surat masuk berhasil diperbarui.');
    }

    public function destroy(SuratMasuk $suratMasuk)
    {
        $suratMasuk->delete();

        return redirect()->route('surat-masuk.index')->with('status', 'Surat masuk berhasil dihapus.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'nomor_surat' => ['required', 'string', 'max:100'],
            'asal_surat' => ['required', 'string', 'max:150'],
            'tanggal_surat' => ['required', 'date'],
            'tanggal_terima' => ['required', 'date'],
            'perihal' => ['required', 'string', 'max:200'],
            'disposisi_ke' => ['nullable', 'string', 'max:100'],
            'catatan_disposisi' => ['nullable', 'string'],
        ]);
    }
}
