<?php

namespace Modules\Instruktur\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NilaiController extends Controller
{
    public function index(Kursus $kursus)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || ($kursus->instruktur_id !== $instruktur->id && $kursus->instruktur_id_2 !== $instruktur->id)) {
            abort(403);
        }
        $pendaftarans = $kursus->pendaftarans()->with('peserta.user', 'score')->get();
        return view('instruktur::instruktur.nilai.index', compact('kursus', 'pendaftarans'));
    }

    public function create(Pendaftaran $pendaftaran)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || ($pendaftaran->kursus->instruktur_id !== $instruktur->id && $pendaftaran->kursus->instruktur_id_2 !== $instruktur->id)) {
            abort(403);
        }
        return view('instruktur::instruktur.nilai.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $pendaftaran_id = $request->input('pendaftaran_id');
        $pendaftaran = Pendaftaran::findOrFail($pendaftaran_id);
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || ($pendaftaran->kursus->instruktur_id !== $instruktur->id && $pendaftaran->kursus->instruktur_id_2 !== $instruktur->id)) {
            abort(403);
        }
        $request->validate([
            'listening' => 'nullable|numeric|min:0|max:100',
            'speaking' => 'nullable|numeric|min:0|max:100',
            'reading' => 'nullable|numeric|min:0|max:100',
            'writing' => 'nullable|numeric|min:0|max:100',
            'assignment' => 'nullable|numeric|min:0|max:100',
            'uktp' => 'nullable|numeric|min:0|max:100',
            'ukap' => 'nullable|numeric|min:0|max:100',
            'var1' => 'nullable|numeric|min:0|max:100',
            'var2' => 'nullable|numeric|min:0|max:100',
            'var3' => 'nullable|numeric|min:0|max:100',
            'var4' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'nullable|string'
        ]);
        $data = $request->only(['listening', 'speaking', 'reading', 'writing', 'assignment', 'uktp', 'ukap', 'var1', 'var2', 'var3', 'var4', 'keterangan']);
        $data['pendaftaran_id'] = $pendaftaran_id;
        $data['evaluated_by'] = $instruktur->id;
        $data['evaluated_at'] = now();
        $scores = array_filter([$data['listening'], $data['speaking'], $data['reading'], $data['writing'], $data['assignment'], $data['uktp'], $data['ukap'], $data['var1'], $data['var2'], $data['var3'], $data['var4']]);
        if (count($scores) > 0) {
            $data['final_score'] = array_sum($scores) / count($scores);
        }
        Score::create($data);
        return redirect()->route('instruktur.nilai.index', $pendaftaran->kursus_id)->with('success', 'Nilai berhasil disimpan');
    }

    public function edit(Score $nilai)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || $nilai->evaluated_by !== $instruktur->id) {
            abort(403);
        }
        return view('instruktur::instruktur.nilai.edit', compact('nilai'));
    }

    public function show(Score $nilai)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || $nilai->evaluated_by !== $instruktur->id) {
            abort(403);
        }
        return response()->json($nilai);
    }

    public function update(Request $request, Score $nilai)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || $nilai->evaluated_by !== $instruktur->id) {
            abort(403);
        }
        $request->validate([
            'listening' => 'nullable|numeric|min:0|max:100',
            'speaking' => 'nullable|numeric|min:0|max:100',
            'reading' => 'nullable|numeric|min:0|max:100',
            'writing' => 'nullable|numeric|min:0|max:100',
            'assignment' => 'nullable|numeric|min:0|max:100',
            'uktp' => 'nullable|numeric|min:0|max:100',
            'ukap' => 'nullable|numeric|min:0|max:100',
            'var1' => 'nullable|numeric|min:0|max:100',
            'var2' => 'nullable|numeric|min:0|max:100',
            'var3' => 'nullable|numeric|min:0|max:100',
            'var4' => 'nullable|numeric|min:0|max:100',
            'keterangan' => 'nullable|string'
        ]);
        $data = $request->only(['listening', 'speaking', 'reading', 'writing', 'assignment', 'uktp', 'ukap', 'var1', 'var2', 'var3', 'var4', 'keterangan']);
        $scores = array_filter([$data['listening'], $data['speaking'], $data['reading'], $data['writing'], $data['assignment'], $data['uktp'], $data['ukap'], $data['var1'], $data['var2'], $data['var3'], $data['var4']]);
        if (count($scores) > 0) {
            $data['final_score'] = array_sum($scores) / count($scores);
        }
        $nilai->update($data);
        return redirect()->route('instruktur.nilai.index', $nilai->pendaftaran->kursus_id)->with('success', 'Nilai berhasil diperbarui');
    }

    public function destroy(Score $nilai)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || $nilai->evaluated_by !== $instruktur->id) {
            abort(403);
        }
        $kursus_id = $nilai->pendaftaran->kursus_id;
        $nilai->delete();
        return redirect()->route('instruktur.nilai.index', $kursus_id)->with('success', 'Nilai berhasil dihapus');
    }
}
