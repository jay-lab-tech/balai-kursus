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
        return view('instruktur.risalah.index', compact('kursus', 'risalahs'));
    }

    public function create(Kursus $kursus)
    {
        return view('instruktur.risalah.create', compact('kursus'));
    }

    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $risalah = Risalah::create([
            'kursus_id' => $kursus->id,
            'nama' => $request->nama
        ]);

        return redirect()->route('instruktur.risalah.index', $kursus->id)
            ->with('success', 'Risalah dibuat');
    }
}
