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
    /**
     * Export nilai peserta ke CSV
     */
    public function export(Kursus $kursus)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || !\App\Models\InstrukturKursusLevel::where('instruktur_id', $instruktur->id)->where('kursus_id', $kursus->id)->exists()) {
            abort(403);
        }
        $pendaftarans = $kursus->pendaftarans()->with('peserta.user', 'score')->get();
        $csv = "Nama Peserta,Listening,Speaking,Reading,Writing,Final Score\n";
        foreach ($pendaftarans as $p) {
            $csv .= sprintf(
                '"%s","%s","%s","%s","%s","%s"' . "\n",
                $p->peserta->user->name,
                $p->score->listening ?? '-',
                $p->score->speaking ?? '-',
                $p->score->reading ?? '-',
                $p->score->writing ?? '-',
                $p->score->final_score ?? '-'
            );
        }
        return response($csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="nilai_peserta.csv"');
    }
    public function index(Kursus $kursus)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || !\App\Models\InstrukturKursusLevel::where('instruktur_id', $instruktur->id)->where('kursus_id', $kursus->id)->exists()) {
            abort(403);
        }
        $query = $kursus->pendaftarans()->with('peserta.user', 'score');
        if ($search = request('search')) {
            $query->whereHas('peserta.user', function($q) use ($search) {
                $q->where('name', 'like', "%$search%");
            });
        }
        if ($filter = request('filter')) {
            if ($filter == 'lulus') {
                $query->whereHas('score', function($q) {
                    $q->where('final_score', '>=', 60);
                });
            } elseif ($filter == 'tidak_lulus') {
                $query->whereHas('score', function($q) {
                    $q->where('final_score', '<', 60);
                });
            }
        }
        $pendaftarans = $query->get();
        return view('instruktur::instruktur.nilai.index', compact('kursus', 'pendaftarans'));
    }

    public function create(Pendaftaran $pendaftaran)
    {
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || !\App\Models\InstrukturKursusLevel::where('instruktur_id', $instruktur->id)->where('kursus_id', $pendaftaran->kursus_id)->exists()) {
            abort(403);
        }
        return view('instruktur::instruktur.nilai.create', compact('pendaftaran'));
    }

    public function store(Request $request)
    {
        $pendaftaran_id = $request->input('pendaftaran_id');
        $pendaftaran = Pendaftaran::findOrFail($pendaftaran_id);
        $instruktur = Auth::user()->instruktur;
        if (!$instruktur || !\App\Models\InstrukturKursusLevel::where('instruktur_id', $instruktur->id)->where('kursus_id', $pendaftaran->kursus_id)->exists()) {
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
        $data['jenis'] = \App\Models\Score::TYPE_COURSE;
        $data['evaluated_by'] = $instruktur->id;
        $data['evaluated_at'] = now();
        $scores = array_filter([$data['listening'], $data['speaking'], $data['reading'], $data['writing'], $data['assignment'], $data['uktp'], $data['ukap'], $data['var1'], $data['var2'], $data['var3'], $data['var4']]);
        if (count($scores) > 0) {
            $data['final_score'] = array_sum($scores) / count($scores);
        }
        Score::updateOrCreate(
            [
                'pendaftaran_id' => $pendaftaran_id,
                'jenis' => \App\Models\Score::TYPE_COURSE,
            ],
            $data
        );
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
        $data['jenis'] = \App\Models\Score::TYPE_COURSE;
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
