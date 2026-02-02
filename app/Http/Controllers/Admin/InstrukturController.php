<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instruktur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class InstrukturController extends Controller
{
    public function index()
    {
        $instrukturs = Instruktur::with('user')->get();
        return view('admin.instruktur.index', compact('instrukturs'));
    }

    public function create()
    {
        return view('admin.instruktur.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required|min:6',
            'nama_instr' => 'required',
            'spesialisasi' => 'required'
        ]);

        // buat akun login
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'instruktur'
        ]);

        // buat profil instruktur
        Instruktur::create([
            'user_id' => $user->id,
            'nama_instr' => $request->nama_instr,
            'spesialisasi' => $request->spesialisasi
        ]);

        return redirect('/admin/instruktur')->with('success', 'Instruktur berhasil ditambahkan');
    }

    public function edit(Instruktur $instruktur)
    {
        return view('admin.instruktur.edit', compact('instruktur'));
    }

    public function update(Request $request, Instruktur $instruktur)
    {
        // update user
        $instruktur->user->update([
            'name' => $request->name,
            'email' => $request->email
        ]);

        // update instruktur
        $instruktur->update([
            'nama_instr' => $request->nama_instr,
            'spesialisasi' => $request->spesialisasi
        ]);

        return redirect('/admin/instruktur')->with('success', 'Instruktur berhasil diupdate');
    }

    public function destroy(Instruktur $instruktur)
    {
        // hapus user = otomatis hapus instruktur (cascade)
        $instruktur->user->delete();
        return back()->with('success', 'Instruktur dihapus');
    }
}
