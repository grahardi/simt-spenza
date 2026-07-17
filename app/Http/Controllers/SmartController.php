<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Smart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SmartController extends Controller
{
    private array $jamTersedia = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    /** Pengganti smart.php - kalender 5 hari x jam, tampilkan yang sudah dibooking. */
    public function kalender(Request $request)
    {
        $mulai = $request->date('tgl') ?? Carbon::today();
        $mulai = Carbon::parse($mulai);
        $tanggalList = collect(range(0, 4))->map(fn ($i) => $mulai->copy()->addDays($i));

        $booking = Smart::with('guru')
            ->whereBetween('tanggal', [$tanggalList->first()->toDateString(), $tanggalList->last()->toDateString()])
            ->get()
            ->groupBy(fn ($s) => $s->tanggal->toDateString().'-'.$s->jam);

        return view('smart.kalender', [
            'tanggalList' => $tanggalList,
            'jamTersedia' => $this->jamTersedia,
            'booking' => $booking,
            'mulai' => $mulai,
        ]);
    }

    /** Pengganti pinjamsmart.php - form booking 1 slot. */
    public function pinjam(string $tanggal, int $jam)
    {
        return view('smart.pinjam', ['tanggal' => $tanggal, 'jam' => $jam]);
    }

    /** Pengganti prosessmart.php - simpan booking. */
    public function simpan(Request $request, string $tanggal, int $jam)
    {
        $data = $request->validate([
            'keterangan' => ['nullable', 'string', 'max:50'],
        ]);

        /** @var Member $member */
        $member = Auth::guard('member')->user();

        Smart::create([
            'tanggal' => $tanggal,
            'jam' => $jam,
            'idguru' => $member->id_guru,
            'ket' => $data['keterangan'] ?: 'Pelajaran',
        ]);

        return redirect()->route('smart.kalender')->with('status', 'Ruang berhasil dibooking.');
    }
}
