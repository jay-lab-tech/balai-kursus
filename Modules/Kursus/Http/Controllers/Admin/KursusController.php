<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kursus;
use App\Models\Program;
use App\Models\Level;
use App\Models\Instruktur;
use App\Models\User;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use App\Models\Pembayaran;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class KursusController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }
    public function index()
    {
        $kursus = Kursus::with('program', 'level', 'instruktur')->get();
        return view('kursus::admin.kursus.index', compact('kursus'));
    }

    public function create()
    {
        $program = Program::all();
        $level = Level::all();
        $instruktur = Instruktur::all();

        return view('kursus::admin.kursus.create', compact(
            'program',
            'level',
            'instruktur'
        ));
    }

    public function store(Request $request)
    {
        Kursus::create($request->all());
        return redirect('/admin/kursus');
    }

    public function edit(Kursus $kursus)
    {
        $program = Program::all();
        $level = Level::all();
        $instruktur = Instruktur::all();

        return view('kursus::admin.kursus.edit', compact(
            'kursus',
            'program',
            'level',
            'instruktur'
        ));
    }

    public function update(Request $request, Kursus $kursus)
    {
        $kursus->update($request->all());
        return redirect('/admin/kursus')->with('success', 'Berhasil update');
    }


    public function destroy(Kursus $kursus)
    {
        $kursus->delete();
        return back();
    }

    public function peserta(Kursus $kursus)
    {
        $peserta = $kursus->pendaftarans()
            ->with('peserta.user')
            ->get();

        return view('kursus::admin.kursus.peserta', compact('kursus', 'peserta'));
    }

    public function risalahs(Kursus $kursus)
    {
        $risalahs = $kursus->risalahs()->with('instruktur','absensis')->get();
        return view('kursus::admin.kursus.risalah', compact('kursus','risalahs'));
    }

    public function absensi(Kursus $kursus)
    {
        $absensis = $kursus->risalahs()->with('absensis.pendaftaran.peserta.user')->get()->pluck('absensis')->flatten();
        return view('kursus::admin.kursus.absensi', compact('kursus','absensis'));
    }

    // Global view: all risalahs across kursus
    public function allRisalahs()
    {
        $risalahs = \App\Models\Risalah::with('kursus','instruktur')->latest()->get();
        return view('kursus::admin.kursus.all_risalah', compact('risalahs'));
    }

    // Global view: all absensi across kursus
    public function allAbsensis()
    {
        $absensis = \App\Models\Absensi::with('risalah','pendaftaran.peserta.user')->latest()->get();
        return view('kursus::admin.kursus.all_absensi', compact('absensis'));
    }
}
