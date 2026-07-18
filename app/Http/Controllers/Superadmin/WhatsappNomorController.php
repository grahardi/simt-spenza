<?php

namespace App\Http\Controllers\Superadmin;

use App\Http\Controllers\Controller;
use App\Models\Siswa;
use App\Models\SiswaWhatsapp;
use Illuminate\Http\Request;

class WhatsappNomorController extends Controller
{
    /** Daftar semua nomor WA yang terhubung ke siswa (1 siswa bisa lebih dari 1 baris, maks 3). */
    public function index(Request $request)
    {
        $nomor = SiswaWhatsapp::with('siswa')
            ->when($request->filled('cari'), function ($q) use ($request) {
                $cari = $request->input('cari');
                $q->where('nomor', 'like', '%'.$cari.'%')
                    ->orWhereHas('siswa', fn ($qq) => $qq->where('nama_lengkap', 'like', '%'.$cari.'%'));
            })
            ->when($request->filled('kelas'), function ($q) use ($request) {
                $q->whereHas('siswa', fn ($qq) => $qq->where('kelas', $request->input('kelas')));
            })
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        $daftarKelas = \App\Models\Kelas::orderBy('nama_kelas')->pluck('nama_kelas');

        return view('superadmin.whatsapp-nomor.index', compact('nomor', 'daftarKelas'));
    }

    /** Hapus massal SEMUA nomor WA untuk 1 kelas sekaligus - dipakai kalau perlu bersihkan data uji coba. */
    public function hapusMassalKelas(Request $request)
    {
        $data = $request->validate(['kelas' => ['required', 'string']]);

        $jumlah = SiswaWhatsapp::whereHas('siswa', fn ($q) => $q->where('kelas', $data['kelas']))->count();
        SiswaWhatsapp::whereHas('siswa', fn ($q) => $q->where('kelas', $data['kelas']))->delete();

        return redirect()->route('superadmin.whatsapp-nomor.index')
            ->with('status', $jumlah.' nomor WhatsApp untuk kelas '.$data['kelas'].' berhasil dihapus.');
    }

    /**
     * Export semua nomor jadi file vCard (.vcf) - untuk di-import manual ke
     * kontak HP yang dipakai bot (Baileys tidak bisa simpan kontak otomatis,
     * ini batasan teknis, bukan belum diprogram). Format nama:
     * nomor pertama per siswa -> "Wali {nama} {kelas}"
     * nomor ke-2/3 -> "{nama} {kelas} 2" / "{nama} {kelas} 3"
     */
    public function exportVcf()
    {
        $semua = SiswaWhatsapp::with('siswa')->orderBy('id_siswa')->orderBy('id')->get()->groupBy('id_siswa');

        $vcf = '';
        foreach ($semua as $daftarNomor) {
            $siswa = $daftarNomor->first()->siswa;
            if (!$siswa) {
                continue;
            }

            foreach ($daftarNomor->values() as $i => $n) {
                $nama = $i === 0
                    ? "Wali {$siswa->nama_lengkap} {$siswa->kelas}"
                    : "{$siswa->nama_lengkap} {$siswa->kelas} ".($i + 1);

                $vcf .= "BEGIN:VCARD\r\nVERSION:3.0\r\nFN:{$nama}\r\nTEL;TYPE=CELL:+{$n->nomor}\r\nEND:VCARD\r\n";
            }
        }

        return response($vcf, 200, [
            'Content-Type' => 'text/vcard',
            'Content-Disposition' => 'attachment; filename="kontak-wali-murid.vcf"',
        ]);
    }

    public function create()
    {
        return view('superadmin.whatsapp-nomor.form');
    }

    /** Tambah nomor manual (misal wali murid minta bantuan staf, bukan lewat bot). */
    public function store(Request $request)
    {
        $data = $request->validate([
            'no_induk' => ['required', 'integer'],
            'nomor' => ['required', 'string', 'max:20'],
            'label' => ['nullable', 'string', 'max:20'],
        ]);

        $siswa = Siswa::find($data['no_induk']);
        if (!$siswa) {
            return back()->withInput()->with('status', 'Nomor Induk tidak ditemukan.');
        }

        $nomorBersih = preg_replace('/\D/', '', $data['nomor']);

        if ($siswa->nomorWhatsapp()->where('nomor', $nomorBersih)->exists()) {
            return back()->withInput()->with('status', 'Nomor ini sudah terdaftar untuk siswa tersebut.');
        }

        if ($siswa->nomorWhatsapp()->count() >= SiswaWhatsapp::MAKSIMAL_PER_SISWA) {
            return back()->withInput()->with('status', $siswa->nama_lengkap.' sudah punya '.SiswaWhatsapp::MAKSIMAL_PER_SISWA.' nomor (maksimal). Hapus salah satu dulu kalau mau tambah baru.');
        }

        $siswa->nomorWhatsapp()->create(['nomor' => $nomorBersih, 'label' => $data['label'] ?? null]);

        return redirect()->route('superadmin.whatsapp-nomor.index')->with('status', 'Nomor WhatsApp berhasil ditambahkan untuk '.$siswa->nama_lengkap.'.');
    }

    /** Putuskan (hapus) 1 nomor spesifik - siswa lain / nomor lain tidak terpengaruh. */
    public function putuskan(SiswaWhatsapp $siswaWhatsapp)
    {
        $nama = $siswaWhatsapp->siswa->nama_lengkap ?? 'siswa';
        $siswaWhatsapp->delete();

        return back()->with('status', 'Nomor WhatsApp berhasil diputuskan dari '.$nama.'.');
    }
}
