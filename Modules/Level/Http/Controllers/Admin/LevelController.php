<?php

namespace Modules\Level\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $level = Level::all();
        return view('level::admin.level.index', compact('level'));
    }

    public function create()
    {
        return view('level::admin.level.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'warna' => 'required'
        ]);

        Level::create([
            'nama' => $request->nama,
            'warna' => $request->warna
        ]);
        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil ditambahkan');
    }

    public function edit(Level $level)
    {
        return view('level::admin.level.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $request->validate([
            'nama' => 'required',
            'warna' => 'required'
        ]);

        $level->update([
            'nama' => $request->nama,
            'warna' => $request->warna
        ]);
        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil diupdate');
    }

    public function destroy(Level $level)
    {
        $level->delete();
        return back()->with('success', 'Level dihapus');
    }
}
