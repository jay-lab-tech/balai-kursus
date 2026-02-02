<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Absensi;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Risalah;
use App\Models\User;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index()
    {
        $instruktur = auth()->user()->instruktur;

        $kursus = Kursus::where('instruktur_id', $instruktur->id)
            ->with('program', 'level')
            ->withCount(['pendaftarans as peserta_count', 'risalahs as risalah_count'])
            ->get();

        return view('instruktur.absensi.index', compact('kursus'));
    }

    public function show(Kursus $kursus)
    {
        $risalah = $kursus->risalahs()->latest()->get();
        return view('instruktur.absensi.show', compact('kursus', 'risalah'));
    }

    public function absensi(Risalah $risalah)
    {
        $pendaftaran = $risalah->kursus
            ->pendaftarans()
            ->with('peserta.user')
            ->get();

        return view('instruktur.absensi.form', compact('risalah', 'pendaftaran'));
    }

    public function store(Request $request, Risalah $risalah)
    {
        foreach ($request->absen as $pendaftaran_id => $status) {
            Absensi::updateOrCreate(
                [
                    'risalah_id' => $risalah->id,
                    'pendaftaran_id' => $pendaftaran_id
                ],
                [
                    'status' => $status,
                    'jam_datang' => now()
                ]
            );
        }

        return back()->with('success', 'Absensi tersimpan');
    }
}
