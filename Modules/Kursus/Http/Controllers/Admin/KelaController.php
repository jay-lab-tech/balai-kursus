<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kela;
use Illuminate\Http\Request;

class KelaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $kelas = Kela::latest('id')->paginate(15);
        return view('kursus::admin.kelas.index', compact('kelas'));
    }

    public function create()
    {
        return view('kursus::admin.kelas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        Kela::create($request->all());
        return redirect('/admin/kelas')->with('success', 'Kelas berhasil ditambahkan');
    }

    public function show(Kela $kela)
    {
        return view('kursus::admin.kelas.show', compact('kela'));
    }

    public function edit(Kela $kela)
    {
        return view('kursus::admin.kelas.edit', compact('kela'));
    }

    public function update(Request $request, Kela $kela)
    {
        $request->validate([
            'nama' => 'required|string|unique:kelas,nama,' . $kela->id,
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        $kela->update($request->all());
        return redirect('/admin/kelas')->with('success', 'Kelas berhasil diperbarui');
    }

    public function destroy(Kela $kela)
    {
        $kela->delete();
        return back()->with('success', 'Kelas berhasil dihapus');
    }
}
