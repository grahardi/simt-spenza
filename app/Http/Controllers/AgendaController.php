<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
                // FullCalendar: tanggal selesai bersifat "eksklusif", jadi +1 hari
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
        $agenda->delete();

        return redirect()->route('agenda.index')->with('status', 'Agenda berhasil dihapus.');
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
