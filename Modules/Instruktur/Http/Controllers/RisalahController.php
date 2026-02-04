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

    public function edit(Risalah $risalah)
    {
        // ensure the logged instruktur owns the kursus
        $instruktur = auth()->user()->instruktur;
        if (!$instruktur || $instruktur->id !== $risalah->instruktur_id) {
            abort(403);
        }

        return view('instruktur::instruktur.risalah.edit', compact('risalah'));
    }

    public function update(Request $request, Risalah $risalah)
    {
        $instruktur = auth()->user()->instruktur;
        if (!$instruktur || $instruktur->id !== $risalah->instruktur_id) {
            abort(403);
        }

        $request->validate([
            'materi' => 'required|string',
            'catatan' => 'nullable|string'
        ]);

        $risalah->update($request->only(['materi','catatan']));

        return redirect("/instruktur/kursus/{$risalah->kursus_id}/risalah")->with('success', 'Risalah diperbarui');
    }
}
