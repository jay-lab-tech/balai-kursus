<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hari;
use Illuminate\Http\Request;

class HariController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $haris = Hari::orderBy('urutan')->paginate(15);
        return view('kursus::admin.hari.index', compact('haris'));
    }

    public function create()
    {
        return view('kursus::admin.hari.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:haris',
            'urutan' => 'required|integer|min:1|max:7|unique:haris'
        ]);

        Hari::create($request->all());
        return redirect('/admin/hari')->with('success', 'Hari berhasil ditambahkan');
    }

    public function edit(Hari $hari)
    {
        return view('kursus::admin.hari.edit', compact('hari'));
    }

    public function update(Request $request, Hari $hari)
    {
        $request->validate([
            'nama' => 'required|string|unique:haris,nama,' . $hari->id,
            'urutan' => 'required|integer|min:1|max:7|unique:haris,urutan,' . $hari->id
        ]);

        $hari->update($request->all());
        return redirect('/admin/hari')->with('success', 'Hari berhasil diperbarui');
    }

    public function destroy(Hari $hari)
    {
        $hari->delete();
        return back()->with('success', 'Hari berhasil dihapus');
    }
}
