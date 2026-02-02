<?php

namespace App\Http\Controllers\Instruktur;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Risalah;
use Illuminate\Http\Request;


class RisalahController extends Controller
{
    public function index(Kursus $kursus)
    {
        $risalah = $kursus->risalahs()->latest()->get();
        return view('instruktur.risalah.index', compact('kursus', 'risalah'));
    }

    public function create(Kursus $kursus)
    {
        return view('instruktur.risalah.create', compact('kursus'));
    }

    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'pertemuan_ke' => 'required|integer',
            'tgl_pertemuan' => 'required|date',
            'materi' => 'required'
        ]);

        Risalah::create([
            'kursus_id' => $kursus->id,
            'instruktur_id' => auth()->user()->instruktur->id,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tgl_pertemuan' => $request->tgl_pertemuan,
            'materi' => $request->materi,
            'catatan' => $request->catatan
        ]);

        return redirect("/instruktur/kursus/{$kursus->id}/risalah")
            ->with('success', 'Pertemuan berhasil dibuat');
    }
}
