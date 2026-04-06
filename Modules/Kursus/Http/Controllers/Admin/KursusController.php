<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Level;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $kursus = Kursus::with(['program', 'level'])
            ->withCount('pendaftarans')
            ->latest('id')
            ->paginate(15);

        return view('kursus::admin.kursus.index', compact('kursus'));
    }

    public function create()
    {
        $program = Program::all();
        $levels = Level::ordered()->get();

        return view('kursus::admin.kursus.create', compact('program', 'levels'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'level_id' => 'required|exists:levels,id',
            'nama' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'harga' => 'required|numeric|min:0',
            'harga_upi' => 'nullable|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:buka,tutup,berjalan',
        ]);

        Kursus::create($request->only([
            'program_id',
            'level_id',
            'nama',
            'periode',
            'tanggal_mulai',
            'tanggal_selesai',
            'harga',
            'harga_upi',
            'kuota',
            'status',
        ]));

        return redirect('/admin/kursus')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function edit(Kursus $kursus)
    {
        $program = Program::all();
        $levels = Level::ordered()->get();

        return view('kursus::admin.kursus.edit', compact('kursus', 'program', 'levels'));
    }

    public function update(Request $request, Kursus $kursus)
    {
        $request->validate([
            'program_id' => 'required|exists:programs,id',
            'level_id' => 'required|exists:levels,id',
            'nama' => 'required|string|max:255',
            'periode' => 'nullable|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'harga' => 'required|numeric|min:0',
            'harga_upi' => 'nullable|numeric|min:0',
            'kuota' => 'required|integer|min:1',
            'status' => 'required|in:buka,tutup,berjalan',
        ]);

        $kursus->update($request->only([
            'program_id',
            'level_id',
            'nama',
            'periode',
            'tanggal_mulai',
            'tanggal_selesai',
            'harga',
            'harga_upi',
            'kuota',
            'status',
        ]));

        return redirect('/admin/kursus')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kursus $kursus)
    {
        $kursus->delete();

        return back()->with('success', 'Kelas dihapus');
    }

    public function peserta(Kursus $kursus)
    {
        $peserta = $kursus->pendaftarans()
            ->with(['peserta.user', 'level', 'placementScore'])
            ->latest('id')
            ->paginate(15);

        return view('kursus::admin.kursus.peserta', compact('kursus', 'peserta'));
    }

    public function risalahs(Kursus $kursus)
    {
        $risalahs = $kursus->risalahs()->with('instruktur', 'absensis')->get();

        return view('kursus::admin.kursus.risalah', compact('kursus', 'risalahs'));
    }

    public function absensi(Kursus $kursus)
    {
        $absensis = $kursus->risalahs()->with('absensis.pendaftaran.peserta.user')->get()->pluck('absensis')->flatten();

        return view('kursus::admin.kursus.absensi', compact('kursus', 'absensis'));
    }

    public function allRisalahs()
    {
        $risalahs = \App\Models\Risalah::with('kursus', 'instruktur')->latest()->get();

        return view('kursus::admin.kursus.all_risalah', compact('risalahs'));
    }

    public function allAbsensis()
    {
        $absensis = \App\Models\Absensi::with('risalah', 'pendaftaran.peserta.user')->latest()->get();

        return view('kursus::admin.kursus.all_absensi', compact('absensis'));
    }

    public function assignLevelForm(Kursus $kursus, $pendaftaranId)
    {
        $pendaftaran = Pendaftaran::with('peserta.user')->findOrFail($pendaftaranId);
        $levels = Level::ordered()->get();

        return view('kursus::admin.kursus.assign-level', compact('kursus', 'pendaftaran', 'levels'));
    }

    public function assignLevel(Request $request, Kursus $kursus, $pendaftaranId)
    {
        $request->validate([
            'level_id' => 'required|exists:levels,id',
        ]);

        $pendaftaran = Pendaftaran::findOrFail($pendaftaranId);
        $pendaftaran->update([
            'level_id' => $request->integer('level_id'),
            'kursus_id' => $kursus->id,
            'program_id' => $kursus->program_id,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_PEMBAYARAN,
            'total_bayar' => $kursus->harga,
            'diklasifikasikan_at' => now(),
        ]);

        \App\Models\PesertaKursusLevel::updateOrCreate(
            [
                'peserta_id' => $pendaftaran->peserta_id,
                'kursus_id' => $kursus->id,
            ],
            [
                'level_id' => $request->integer('level_id'),
                'assigned_at' => now(),
            ]
        );

        return redirect()->route('admin.kursus.peserta', $kursus->id)
            ->with('success', 'Level peserta berhasil diperbarui.');
    }
}
