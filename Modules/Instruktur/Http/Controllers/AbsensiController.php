<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\InstrukturKursusLevel;
use App\Models\Jadwal;
use App\Models\Kursus;
use App\Models\Risalah;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $instruktur = auth()->user()->instruktur;

        $kursusIds = InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
            ->pluck('kursus_id');
        $kursus = Kursus::whereIn('id', $kursusIds)
            ->with('program')
            ->withCount(['pendaftarans as peserta_count', 'risalahs as risalah_count'])
            ->get();

        return view('instruktur::instruktur.absensi.index', compact('kursus'));
    }

    public function show(Kursus $kursus)
    {
        $instruktur = auth()->user()->instruktur;
        if (! $instruktur || ! InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
            ->where('kursus_id', $kursus->id)
            ->exists()) {
            abort(403);
        }

        $risalah = $kursus->risalahs()->latest()->get();

        return view('instruktur::instruktur.absensi.show', compact('kursus', 'risalah'));
    }

    public function absensi(Risalah $risalah)
    {
        $instruktur = auth()->user()->instruktur;
        if (! $instruktur || ! InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
            ->where('kursus_id', $risalah->kursus_id)
            ->exists()) {
            abort(403);
        }

        $pendaftaran = $risalah->kursus
            ->pendaftarans()
            ->with('peserta.user')
            ->get();

        return view('instruktur::instruktur.absensi.form', compact('risalah', 'pendaftaran'));
    }

    public function jadwal()
    {
        $instruktur = auth()->user()->instruktur;

        $kursusIds = InstrukturKursusLevel::where('instruktur_id', optional($instruktur)->id)
            ->pluck('kursus_id');

        $jadwals = Jadwal::whereIn('kursus_id', $kursusIds)
            ->with(['kursus.program', 'lokasi', 'kela', 'hari'])
            ->orderBy('tgl_pertemuan')
            ->orderBy('jam_mulai')
            ->get();

        return view('instruktur::instruktur.jadwal.index', compact('jadwals'));
    }

    public function store(Request $request, Risalah $risalah)
    {
        $instruktur = auth()->user()->instruktur;
        if (! $instruktur || ! InstrukturKursusLevel::where('instruktur_id', $instruktur->id)
            ->where('kursus_id', $risalah->kursus_id)
            ->exists()) {
            abort(403);
        }

        $validated = $request->validate([
            'absen' => ['required', 'array'],
            'absen.*' => ['required', 'in:H,S,I,A'],
        ]);

        foreach ($validated['absen'] as $pendaftaranId => $status) {
            Absensi::updateOrCreate(
                [
                    'risalah_id' => $risalah->id,
                    'pendaftaran_id' => $pendaftaranId,
                ],
                [
                    'status' => $status,
                    'jam_datang' => now(),
                ]
            );
        }

        return back()->with('success', 'Absensi tersimpan');
    }
}
