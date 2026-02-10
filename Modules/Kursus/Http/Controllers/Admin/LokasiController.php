<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lokasi;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index()
    {
        $lokasis = Lokasi::latest('id')->paginate(15);
        return view('kursus::admin.lokasi.index', compact('lokasis'));
    }

    public function create()
    {
        return view('kursus::admin.lokasi.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:lokasis',
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        Lokasi::create($request->all());
        return redirect('/admin/lokasi')->with('success', 'Lokasi berhasil ditambahkan');
    }

    public function show(Lokasi $lokasi)
    {
        return view('kursus::admin.lokasi.show', compact('lokasi'));
    }

    public function edit(Lokasi $lokasi)
    {
        return view('kursus::admin.lokasi.edit', compact('lokasi'));
    }

    public function update(Request $request, Lokasi $lokasi)
    {
        $request->validate([
            'nama' => 'required|string|unique:lokasis,nama,' . $lokasi->id,
            'alamat' => 'required|string',
            'no_telp' => 'required|string',
            'kota' => 'required|string',
            'provinsi' => 'required|string',
            'keterangan' => 'nullable|string'
        ]);

        $lokasi->update($request->all());
        return redirect('/admin/lokasi')->with('success', 'Lokasi berhasil diperbarui');
    }

    public function destroy(Lokasi $lokasi)
    {
        $lokasi->delete();
        return back()->with('success', 'Lokasi berhasil dihapus');
    }
}
