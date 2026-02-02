<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\Pendaftaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    public function index()
    {
        $pesertas = Peserta::with('user')->get();
        return view('admin.peserta.index', compact('pesertas'));
    }

    public function create()
    {
        return view('admin.peserta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nomor_peserta' => 'required|unique:pesertas',
            'no_hp' => 'required'
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'peserta'
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'nomor_peserta' => $request->nomor_peserta,
                'no_hp' => $request->no_hp,
                'instansi' => $request->instansi
            ]);
        });

        return redirect('/admin/peserta')->with('success', 'Peserta berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);
        return view('admin.peserta.edit', compact('peserta'));
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::findOrFail($id);

        $peserta->user->update([
            'name' => $request->nama,
            'email' => $request->email,
        ]);

        $peserta->update([
            'nomor_peserta' => $request->nomor_peserta,
            'no_hp' => $request->no_hp,
            'instansi' => $request->instansi,
        ]);

        return redirect('/admin/peserta');
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->user->delete(); // otomatis hapus peserta karena cascade

        return redirect('/admin/peserta')->with('success', 'Peserta berhasil dihapus');
    }
}
