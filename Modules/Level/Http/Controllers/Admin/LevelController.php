<?php

namespace Modules\Level\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use Illuminate\Http\Request;

class LevelController extends Controller
{
    public function index()
    {
        $level = Level::ordered()->get();

        return view('level::admin.level.index', compact('level'));
    }

    public function create()
    {
        return view('level::admin.level.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'nilai_min' => 'required|numeric|min:0|max:100|lte:nilai_max',
            'nilai_max' => 'required|numeric|min:0|max:100|gte:nilai_min',
            'deskripsi' => 'nullable|string',
        ]);

        Level::create($validated);

        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil ditambahkan');
    }

    public function edit(Level $level)
    {
        return view('level::admin.level.edit', compact('level'));
    }

    public function update(Request $request, Level $level)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'urutan' => 'required|integer|min:1',
            'nilai_min' => 'required|numeric|min:0|max:100|lte:nilai_max',
            'nilai_max' => 'required|numeric|min:0|max:100|gte:nilai_min',
            'deskripsi' => 'nullable|string',
        ]);

        $level->update($validated);

        return redirect()->route('admin.level.index')
            ->with('success', 'Level berhasil diupdate');
    }

    public function destroy(Level $level)
    {
        $level->delete();

        return back()->with('success', 'Level dihapus');
    }
}
