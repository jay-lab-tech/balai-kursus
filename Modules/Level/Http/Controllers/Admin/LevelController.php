<?php

namespace Modules\Level\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Models\Program;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $level = Level::with('program')->get();
        return view('level::admin.level.index', compact('level'));
    }

    public function create()
    {
        $program = Program::all();
        return view('level::admin.level.create', compact('program'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'program_id' => 'required',
            'nama' => 'required'
        ]);

        Level::create($request->all());
        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil ditambahkan');
    }

    public function edit(Level $level)
    {
        $program = Program::all();
        return view('level::admin.level.edit', compact('level', 'program'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'program_id' => 'required',
            'nama' => 'required'
        ]);

        $level->update($request->all());
        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil diupdate');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return back()->with('success', 'Level dihapus');
    }
}
