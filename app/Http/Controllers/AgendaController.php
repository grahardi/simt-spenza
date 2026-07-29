<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\AgendaFoto;
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
            ->where('tanggal_mulai', '<=', now('Asia/Jakarta')->toDateString())
            ->orderByDesc('tanggal_mulai')
            ->paginate(15);

        return view('agenda.list-sudah', compact('agenda'));
    }

    /** Agenda Mendatang - kegiatan yang BELUM dilaksanakan. */
    public function mendatang()
    {
        $agenda = Agenda::withCount('foto')
            ->where('tanggal_mulai', '>', now('Asia/Jakarta')->toDateString())
            ->orderBy('tanggal_mulai')
            ->paginate(15);

        return view('agenda.mendatang', compact('agenda'));
    }

    public function create()
    {
        return view('agenda.form', ['agenda' => new Agenda()]);
    }

    public function store(Request $request)
    {
        $data = $this->validasi($request);
        $data['dibuat_oleh'] = Auth::guard('member')->id();

        Agenda::create($data);

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil ditambahkan.');
    }

    public function edit(Agenda $agenda)
    {
        return view('agenda.form', compact('agenda'));
    }

    public function update(Request $request, Agenda $agenda)
    {
        $agenda->update($this->validasi($request));

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil diperbarui.');
    }

    public function destroy(Agenda $agenda)
    {
        foreach ($agenda->foto as $f) {
            Storage::disk('public')->delete($f->path);
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

    private function validasi(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:200'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['nullable', 'date', 'after_or_equal:tanggal_mulai'],
            'keterangan' => ['nullable', 'string', 'max:1000'],
            'kategori' => ['required', 'in:libur,kbm,ujian,kegiatan'],
        ]);
    }
}
