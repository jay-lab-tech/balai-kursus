<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instruktur;
use App\Models\Pendaftaran;
use App\Models\Score;
use App\Services\PendaftaranPlacementService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function export()
    {
        $date = date('Ymd_His');
        $filename = "balai_kursus_upi_hasil_tes_penempatan_{$date}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\NilaiExport, $filename);
    }

    public function index(Request $request)
    {
        $query = Pendaftaran::with(['peserta.user', 'program', 'level', 'kursus', 'placementScore.evaluator'])
            ->whereNotNull('program_id');

        if ($search = $request->get('q')) {
            $query->where(function ($builder) use ($search) {
                $builder->where('nomor', 'like', "%{$search}%")
                    ->orWhere('status_pendaftaran', 'like', "%{$search}%")
                    ->orWhereHas('peserta.user', function ($userQuery) use ($search) {
                        $userQuery->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                    })
                    ->orWhereHas('program', function ($programQuery) use ($search) {
                        $programQuery->where('nama', 'like', "%{$search}%");
                    });
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status_pendaftaran', $status);
        }

        $pendaftarans = $query->latest('id')
            ->paginate(15)
            ->appends($request->only('q', 'status'));

        return view('kursus::admin.score.index', compact('pendaftarans'));
    }

    public function create()
    {
        $selectedPendaftaranId = request('pendaftaran_id');

        $pendaftarans = Pendaftaran::with(['peserta.user', 'program'])
            ->whereNotNull('program_id')
            ->whereDoesntHave('placementScore')
            ->latest('id')
            ->get();

        $instrukturs = Instruktur::with('user')->get();

        return view('kursus::admin.score.create', compact('pendaftarans', 'instrukturs', 'selectedPendaftaranId'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => [
                'required',
                'exists:pendaftarans,id',
                Rule::unique('scores', 'pendaftaran_id')->where(fn ($query) => $query->where('jenis', Score::TYPE_PLACEMENT)),
            ],
            'listening' => 'required|integer|min:0|max:100',
            'speaking' => 'required|integer|min:0|max:100',
            'reading' => 'required|integer|min:0|max:100',
            'writing' => 'required|integer|min:0|max:100',
            'assignment' => 'required|integer|min:0|max:100',
            'uktp' => 'nullable|integer|min:0|max:100',
            'ukap' => 'nullable|integer|min:0|max:100',
            'var1' => 'nullable|string',
            'var2' => 'nullable|string',
            'var3' => 'nullable|string',
            'var4' => 'nullable|string',
            'final_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pass,fail,pending',
            'evaluated_by' => 'required|exists:instrukturs,id',
            'evaluated_at' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $score = Score::create(array_merge($validated, [
            'jenis' => Score::TYPE_PLACEMENT,
        ]));

        $result = app(PendaftaranPlacementService::class)->placeFromScore($score);

        return redirect('/admin/score')->with('success', $result['message']);
    }

    public function show(Score $score)
    {
        abort_unless($score->jenis === Score::TYPE_PLACEMENT, 404);

        $score->load(['pendaftaran.peserta.user', 'pendaftaran.program', 'pendaftaran.level', 'pendaftaran.kursus', 'evaluator']);

        return view('kursus::admin.score.show', compact('score'));
    }

    public function edit(Score $score)
    {
        abort_unless($score->jenis === Score::TYPE_PLACEMENT, 404);

        $pendaftarans = Pendaftaran::with(['peserta.user', 'program'])
            ->whereNotNull('program_id')
            ->latest('id')
            ->get();

        $instrukturs = Instruktur::with('user')->get();
        $score->load('evaluator');

        return view('kursus::admin.score.edit', compact('score', 'pendaftarans', 'instrukturs'));
    }

    public function update(Request $request, Score $score)
    {
        abort_unless($score->jenis === Score::TYPE_PLACEMENT, 404);

        $validated = $request->validate([
            'pendaftaran_id' => [
                'required',
                'exists:pendaftarans,id',
                Rule::unique('scores', 'pendaftaran_id')
                    ->ignore($score->id)
                    ->where(fn ($query) => $query->where('jenis', Score::TYPE_PLACEMENT)),
            ],
            'listening' => 'required|integer|min:0|max:100',
            'speaking' => 'required|integer|min:0|max:100',
            'reading' => 'required|integer|min:0|max:100',
            'writing' => 'required|integer|min:0|max:100',
            'assignment' => 'required|integer|min:0|max:100',
            'uktp' => 'nullable|integer|min:0|max:100',
            'ukap' => 'nullable|integer|min:0|max:100',
            'var1' => 'nullable|string',
            'var2' => 'nullable|string',
            'var3' => 'nullable|string',
            'var4' => 'nullable|string',
            'final_score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:pass,fail,pending',
            'evaluated_by' => 'required|exists:instrukturs,id',
            'evaluated_at' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        $score->update(array_merge($validated, [
            'jenis' => Score::TYPE_PLACEMENT,
        ]));

        $result = app(PendaftaranPlacementService::class)->placeFromScore($score->fresh());

        return redirect('/admin/score')->with('success', $result['message']);
    }

    public function destroy(Score $score)
    {
        abort_unless($score->jenis === Score::TYPE_PLACEMENT, 404);

        $score->delete();

        return back()->with('success', 'Hasil tes penempatan berhasil dihapus dan placement peserta direset.');
    }
}
