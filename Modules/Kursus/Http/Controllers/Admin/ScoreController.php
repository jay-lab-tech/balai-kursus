<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Score;
use App\Models\Pendaftaran;
use App\Models\Instruktur;
use Illuminate\Http\Request;

class ScoreController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    /**
     * Display all scores across all kursus
     */
    public function index(Request $request)
    {
        $q = $request->get('q');
        $sortBy = $request->get('sort_by');
        $sortDir = strtolower($request->get('sort_dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $query = Score::with('pendaftaran.peserta.user', 'pendaftaran.kursus', 'evaluator');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->whereHas('pendaftaran.peserta.user', function ($u) use ($q) {
                    $u->where('name', 'like', "%{$q}%");
                })
                ->orWhereHas('pendaftaran.kursus', function ($k) use ($q) {
                    $k->where('nama', 'like', "%{$q}%");
                })
                ->orWhereHas('pendaftaran.peserta', function ($p) use ($q) {
                    $p->where('nomor_peserta', 'like', "%{$q}%");
                });
            });
        }

        // Whitelist sortable columns and apply ordering safely.
        $allowed = ['participant', 'kursus', 'final_score', 'evaluated_at', 'status'];

        if (in_array($sortBy, $allowed)) {
            switch ($sortBy) {
                case 'participant':
                    $query = $query->select('scores.*')
                        ->join('pendaftarans', 'scores.pendaftaran_id', '=', 'pendaftarans.id')
                        ->join('pesertas', 'pendaftarans.peserta_id', '=', 'pesertas.id')
                        ->join('users', 'pesertas.user_id', '=', 'users.id')
                        ->orderBy('users.name', $sortDir);
                    break;
                case 'kursus':
                    $query = $query->select('scores.*')
                        ->join('pendaftarans', 'scores.pendaftaran_id', '=', 'pendaftarans.id')
                        ->join('kursus', 'pendaftarans.kursus_id', '=', 'kursus.id')
                        ->orderBy('kursus.nama', $sortDir);
                    break;
                case 'final_score':
                    $query = $query->orderBy('final_score', $sortDir);
                    break;
                case 'evaluated_at':
                    $query = $query->orderBy('evaluated_at', $sortDir);
                    break;
                case 'status':
                    $query = $query->orderBy('status', $sortDir);
                    break;
            }
        } else {
            $query = $query->latest('scores.id');
        }

        $scores = $query->paginate(15)->appends($request->only('q', 'sort_by', 'sort_dir'));

        return view('kursus::admin.score.index', compact('scores', 'q', 'sortBy', 'sortDir'));
    }

    /**
     * Display scores for a specific kursus
     */
    public function byKursus($kursusId)
    {
        $scores = Score::whereHas('pendaftaran', function ($query) use ($kursusId) {
            $query->where('kursus_id', $kursusId);
        })->with('pendaftaran.peserta.user', 'evaluator')
            ->latest()
            ->get();

        return view('kursus::admin.score.by_kursus', compact('scores', 'kursusId'));
    }

    /**
     * Create form for new score
     */
    public function create()
    {
        $pendaftarans = Pendaftaran::with('peserta.user', 'kursus')
            ->where('status_pembayaran', '!=', 'pending')
            ->get();
        $instrukturs = Instruktur::with('user')->get();

        return view('kursus::admin.score.create', compact('pendaftarans', 'instrukturs'));
    }

    /**
     * Store a newly created score
     */
    public function store(Request $request)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id|unique:scores,pendaftaran_id',
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
            'keterangan' => 'nullable|string'
        ]);

        Score::create($request->all());

        return redirect('/admin/score')->with('success', 'Nilai berhasil ditambahkan');
    }

    /**
     * Show score details
     */
    public function show(Score $score)
    {
        $score->load('pendaftaran.peserta.user', 'pendaftaran.kursus', 'evaluator');
        return view('kursus::admin.score.show', compact('score'));
    }

    /**
     * Show edit form
     */
    public function edit(Score $score)
    {
        $pendaftarans = Pendaftaran::with('peserta.user', 'kursus')->get();
        $instrukturs = Instruktur::all();
        $score->load('evaluator');

        return view('kursus::admin.score.edit', compact('score', 'pendaftarans', 'instrukturs'));
    }

    /**
     * Update score
     */
    public function update(Request $request, Score $score)
    {
        $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
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
            'keterangan' => 'nullable|string'
        ]);

        $score->update($request->all());

        return redirect('/admin/score')->with('success', 'Nilai berhasil diperbarui');
    }

    /**
     * Delete score
     */
    public function destroy(Score $score)
    {
        $score->delete();
        return back()->with('success', 'Nilai berhasil dihapus');
    }
}
