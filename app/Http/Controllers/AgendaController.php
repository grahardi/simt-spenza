<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaBerkasLainnya;
use App\Models\AgendaFoto;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AgendaController extends Controller
{
    public function index()
    {
        return view('agenda.index');
    }

    /** Data event buat FullCalendar (JSON). */
    public function data()
    {
        $agenda = Agenda::orderBy('tanggal_mulai')->get();

        $events = $agenda->map(function ($a) {
            return [
                'id' => $a->id,
                'title' => $a->judul,
                'start' => $a->tanggal_mulai->toDateString(),
                'end' => $a->tanggal_selesai ? $a->tanggal_selesai->copy()->addDay()->toDateString() : null,
                'color' => Agenda::KATEGORI_WARNA[$a->kategori] ?? '#6c757d',
                'extendedProps' => [
                    'keterangan' => $a->keterangan,
                    'kategori' => Agenda::KATEGORI_LABEL[$a->kategori] ?? $a->kategori,
                ],
            ];
        });

        return response()->json($events);
    }

    /** Agenda List - kegiatan yang SUDAH dilaksanakan (tanggal mulai <= hari ini). */
    public function listSudah()
    {
        $agenda = Agenda::withCount('foto')
            ->with('penanggungJawab')
            ->where('tanggal_mulai', '<=', now('Asia/Jakarta')->toDateString())
            ->orderByDesc('tanggal_mulai')
            ->paginate(15);

        return view('agenda.list-sudah', compact('agenda'));
    }

    /** Agenda Mendatang - kegiatan yang BELUM dilaksanakan. */
    public function mendatang()
    {
        $agenda = Agenda::withCount('foto')
            ->with('penanggungJawab')
            ->where('tanggal_mulai', '>', now('Asia/Jakarta')->toDateString())
            ->orderBy('tanggal_mulai')
            ->paginate(15);

        return view('agenda.mendatang', compact('agenda'));
    }

    public function create()
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('agenda.form', ['agenda' => new Agenda(), 'daftarGuru' => $daftarGuru]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['dibuat_oleh'] = Auth::guard('member')->id();
        $data = $this->tanganiUploadBerkas($request, $data);

        Agenda::create($data);

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        $daftarGuru = Guru::orderBy('nama')->get();

        return view('agenda.form', compact('agenda', 'daftarGuru'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $data = $this->validasi($request);
        $data = $this->tanganiUploadBerkas($request, $data, $agenda);

        $agenda->update($data);

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        foreach ($agenda->foto as $f) {
            Storage::disk('public')->delete($f->path);
        }
        foreach ($agenda->berkasLainnya as $b) {
            Storage::disk('public')->delete($b->path);
        }
        foreach ([$agenda->berkas_proposal, $agenda->berkas_sk_kepanitiaan, $agenda->berkas_spj] as $path) {
            if ($path) {
                Storage::disk('public')->delete($path);
            }
        }
        $agenda->delete();

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil dihapus.');
    }

    /** Galeri foto 1 agenda - model tampilan grid ala Instagram. */
    public function galeri(Agenda $agenda)
    {
        $agenda->load('foto');

        return view('agenda.galeri', compact('agenda'));
    }

    public function simpanFoto(Request $request, Agenda $agenda)
    {
        $request->validate([
            'foto' => ['required', 'array', 'min:1'],
            'foto.*' => ['image', 'max:8192'],
        ]);

        foreach ($request->file('foto') as $file) {
            $agenda->foto()->create([
                'path' => $file->store('agenda', 'public'),
                'diupload_oleh' => Auth::guard('member')->id(),
            ]);
        }

        return back()->with('status', count($request->file('foto')).' foto berhasil diupload.');
    }

    public function hapusFoto(AgendaFoto $agendaFoto)
    {
        $idAgenda = $agendaFoto->id_agenda;
        Storage::disk('public')->delete($agendaFoto->path);
        $agendaFoto->delete();

        return redirect()->route('agenda.galeri', $idAgenda)->with('status', 'Foto dihapus.');
    }

    /** Upload "Berkas Lainnya" (boleh banyak sekaligus, beda dari Proposal/SK/SPJ yang cuma 1 file). */
    public function simpanBerkasLainnya(Request $request, Agenda $agenda)
    {
        $request->validate([
            'berkas' => ['required', 'array', 'min:1'],
            'berkas.*' => ['file', 'max:10240'],
        ]);

        foreach ($request->file('berkas') as $file) {
            $agenda->berkasLainnya()->create([
                'nama_file' => $file->getClientOriginalName(),
                'path' => $file->store('agenda/berkas-lainnya', 'public'),
            ]);
        }

        return back()->with('status', 'Berkas berhasil diupload.');
    }

    public function hapusBerkasLainnya(AgendaBerkasLainnya $agendaBerkasLainnya)
    {
        $idAgenda = $agendaBerkasLainnya->id_agenda;
        Storage::disk('public')->delete($agendaBerkasLainnya->path);
        $agendaBerkasLainnya->delete();

        return redirect()->route('agenda.edit', $idAgenda)->with('status', 'Berkas dihapus.');
    }

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'kategori' => ['required', 'in:libur,kbm,ujian,kegiatan'],
            'id_penanggung_jawab' => ['nullable', 'integer', 'exists:guru,id_guru'],
            'berkas_proposal' => ['nullable', 'file', 'max:10240'],
            'berkas_sk_kepanitiaan' => ['nullable', 'file', 'max:10240'],
            'berkas_spj' => ['nullable', 'file', 'max:10240'],
        ]);
    }

    /** Simpan 3 berkas utama (Proposal/SK Kepanitiaan/SPJ) - upload baru mengganti yang lama. */
    private function tanganiUploadBerkas(Request $request, array $data, ?Agenda $agenda = null): array
    {
        foreach (['berkas_proposal', 'berkas_sk_kepanitiaan', 'berkas_spj'] as $field) {
            if ($request->hasFile($field)) {
                if ($agenda && $agenda->{$field}) {
                    Storage::disk('public')->delete($agenda->{$field});
                }
                $data[$field] = $request->file($field)->store('agenda/berkas', 'public');
            } else {
                unset($data[$field]);
            }
        }

        return $data;
    }
}
