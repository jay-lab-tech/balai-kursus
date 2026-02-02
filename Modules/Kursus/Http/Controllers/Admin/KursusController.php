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
}
