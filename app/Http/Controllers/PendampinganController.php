<?php

namespace App\Http\Controllers;

use App\Models\PendampinganFoto;
use App\Models\PendampinganWali;
use App\Models\Siswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PendampinganController extends Controller
{
    private function guruLogin()
    {
        $member = Auth::guard('member')->user();
        $guru = $member->dataGuru;

        abort_if(!$guru, 403, 'Akun ini tidak terhubung ke data guru manapun.');

        return $guru;
    }

    public function index()
    {
        $guru = $this->guruLogin();

        $daftar = PendampinganWali::where('id_guru', $guru->id_guru)
            ->withCount('peserta')
            ->with('fotoTambahan')
            ->orderByDesc('tanggal_waktu')
            ->paginate(15);

        return view('pendampingan.index', compact('guru', 'daftar'));
    }

    /** Galeri Wali - kegiatan pendampingan UMUM dari SEMUA guru wali (bukan cuma diri sendiri). */
    public function galeri()
    {
        $galeri = PendampinganWali::with(['guru', 'fotoTambahan'])
            ->withCount('peserta')
            ->where('visibilitas', 'umum')
            ->whereNotNull('foto')
            ->orderByDesc('tanggal_waktu')
            ->paginate(20);

        return view('pendampingan.galeri', compact('galeri'));
    }

    public function create()
    {
        $guru = $this->guruLogin();

        $siswaWali = Siswa::where('id_guru_wali', $guru->id_guru)
            ->orderBy('nama_lengkap')
            ->get();

        return view('pendampingan.form', ['guru' => $guru, 'siswaWali' => $siswaWali]);
    }

    public function store(Request $request)
    {
        $guru = $this->guruLogin();

        $data = $request->validate([
            'tanggal_waktu' => ['required', 'date'],
            'kategori' => ['required', 'string', 'in:'.implode(',', PendampinganWali::KATEGORI_PILIHAN)],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'max:8192'],
            'foto_tambahan' => ['nullable', 'array'],
            'foto_tambahan.*' => ['image', 'max:8192'],
            'visibilitas' => ['required', 'in:umum,private'],
            'peserta_mode' => ['required', 'in:semua,pilih'],
            'peserta_id' => ['required_if:peserta_mode,pilih', 'array'],
            'peserta_id.*' => ['integer'],
        ]);

        $atribut = [
            'id_guru' => $guru->id_guru,
            'tanggal_waktu' => $data['tanggal_waktu'],
            'kategori' => $data['kategori'],
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'visibilitas' => $data['visibilitas'],
            'peserta_mode' => $data['peserta_mode'],
        ];

        if ($request->hasFile('foto')) {
            $atribut['foto'] = $request->file('foto')->store('pendampingan', 'public');
        }

        $pendampingan = PendampinganWali::create($atribut);

        if ($request->hasFile('foto_tambahan')) {
            foreach ($request->file('foto_tambahan') as $file) {
                $pendampingan->fotoTambahan()->create(['path' => $file->store('pendampingan/tambahan', 'public')]);
            }
        }

        $siswaWali = Siswa::where('id_guru_wali', $guru->id_guru)->pluck('id_member');

        $pesertaIds = $data['peserta_mode'] === 'semua'
            ? $siswaWali
            : collect($data['peserta_id'] ?? [])->intersect($siswaWali); // jaga-jaga cuma boleh dari anak wali sendiri

        $pendampingan->peserta()->sync($pesertaIds);

        return redirect()->route('pendampingan.index')->with('status', 'Pendampingan berhasil dicatat.');
    }

    public function edit(PendampinganWali $pendampingan)
    {
        $guru = $this->guruLogin();
        abort_unless($pendampingan->id_guru === $guru->id_guru, 403, 'Bukan catatan pendampingan Anda.');

        $siswaWali = Siswa::where('id_guru_wali', $guru->id_guru)
            ->orderBy('nama_lengkap')
            ->get();

        $pesertaTerpilih = $pendampingan->peserta()->pluck('id_member')->toArray();

        return view('pendampingan.form', [
            'guru' => $guru,
            'siswaWali' => $siswaWali,
            'pendampingan' => $pendampingan,
            'pesertaTerpilih' => $pesertaTerpilih,
        ]);
    }

    public function update(Request $request, PendampinganWali $pendampingan)
    {
        $guru = $this->guruLogin();
        abort_unless($pendampingan->id_guru === $guru->id_guru, 403, 'Bukan catatan pendampingan Anda.');

        $data = $request->validate([
            'tanggal_waktu' => ['required', 'date'],
            'kategori' => ['required', 'string', 'in:'.implode(',', PendampinganWali::KATEGORI_PILIHAN)],
            'judul' => ['required', 'string', 'max:150'],
            'deskripsi' => ['nullable', 'string', 'max:1000'],
            'foto' => ['nullable', 'image', 'max:8192'],
            'foto_tambahan' => ['nullable', 'array'],
            'foto_tambahan.*' => ['image', 'max:8192'],
            'visibilitas' => ['required', 'in:umum,private'],
            'peserta_mode' => ['required', 'in:semua,pilih'],
            'peserta_id' => ['required_if:peserta_mode,pilih', 'array'],
            'peserta_id.*' => ['integer'],
        ]);

        $atribut = [
            'tanggal_waktu' => $data['tanggal_waktu'],
            'kategori' => $data['kategori'],
            'judul' => $data['judul'],
            'deskripsi' => $data['deskripsi'] ?? null,
            'visibilitas' => $data['visibilitas'],
            'peserta_mode' => $data['peserta_mode'],
        ];

        if ($request->hasFile('foto')) {
            $atribut['foto'] = $request->file('foto')->store('pendampingan', 'public');
        }

        $pendampingan->update($atribut);

        if ($request->hasFile('foto_tambahan')) {
            foreach ($request->file('foto_tambahan') as $file) {
                $pendampingan->fotoTambahan()->create(['path' => $file->store('pendampingan/tambahan', 'public')]);
            }
        }

        $siswaWali = Siswa::where('id_guru_wali', $guru->id_guru)->pluck('id_member');

        $pesertaIds = $data['peserta_mode'] === 'semua'
            ? $siswaWali
            : collect($data['peserta_id'] ?? [])->intersect($siswaWali);

        $pendampingan->peserta()->sync($pesertaIds);

        return redirect()->route('pendampingan.index')->with('status', 'Pendampingan berhasil diperbarui.');
    }

    /** Hapus 1 foto tambahan (dari halaman edit). */
    public function hapusFotoTambahan(PendampinganFoto $fotoTambahan)
    {
        $guru = $this->guruLogin();
        abort_unless($fotoTambahan->pendampingan->id_guru === $guru->id_guru, 403);

        $idPendampingan = $fotoTambahan->id_pendampingan;
        \Illuminate\Support\Facades\Storage::disk('public')->delete($fotoTambahan->path);
        $fotoTambahan->delete();

        return redirect()->route('pendampingan.edit', $idPendampingan)->with('status', 'Foto tambahan dihapus.');
    }
}
