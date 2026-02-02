<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Risalah;
use App\Models\Kursus;
use Illuminate\Http\Request;

class RisalahController extends Controller
{
    public function index(Kursus $kursus)
    {
        $risalahs = $kursus->risalahs()->latest()->get();
        return view('instruktur::instruktur.risalah.index', compact('kursus', 'risalahs'));
    }

    public function create(Kursus $kursus)
    {
        return view('instruktur::instruktur.risalah.create', compact('kursus'));
    }

    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $instruktur = auth()->user()->instruktur;

        $risalah = Risalah::create([
            'kursus_id' => $kursus->id,
            'instruktur_id' => $instruktur->id,
            'pertemuan_ke' => $request->pertemuan_ke ?? $kursus->risalahs()->count() + 1,
            'tgl_pertemuan' => now(),
            'materi' => $request->nama
        ]);

        return redirect("/instruktur/kursus/{$kursus->id}/risalah")
            ->with('success', 'Risalah dibuat');
    }
}
