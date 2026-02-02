<?php

namespace Modules\Program\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index()
    {
        $program = Program::all();
        return view('program::admin.program.index', compact('program'));
    }

    public function create()
    {
        return view('program::admin.program.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        Program::create($request->all());
        return redirect()->route('admin.program.index')
            ->with('success', 'Program berhasil ditambahkan');
    }

    public function edit(Program $program)
    {
        return view('program::admin.program.edit', compact('program'));
    }

    public function update(Request $request, Program $program)
    {
        $request->validate([
            'nama' => 'required'
        ]);

        $program->update($request->all());
        return redirect()->route('admin.program.index')
            ->with('success', 'Program berhasil diupdate');
    }

    public function destroy(Program $program)
    {
        $program->delete();
        return back()->with('success', 'Program dihapus');
    }

    public function getLevels(Program $program)
    {
        return response()->json($program->levels);
    }
}
