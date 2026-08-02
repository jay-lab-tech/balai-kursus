<?php

namespace Modules\Instruktur\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instruktur;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class InstrukturController extends Controller
{
    public function index()
    {
        // Jumlah kelas diampu ikut ditarik supaya daftar tidak memicu kueri
        // tambahan per baris. Dihitung unik karena satu kursus bisa muncul
        // lebih dari sekali di pivot bila ditugaskan pada beberapa level.
        $instrukturs = Instruktur::with('user')
            ->withCount(['kursuses' => fn ($query) => $query->select(DB::raw('count(distinct kursuses.id)'))])
            ->get();

        return view('instruktur::admin.instruktur.index', compact('instrukturs'));
    }

    public function create()
    {
        return view('instruktur::admin.instruktur.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'nama_instr' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'instruktur',
                'email_verified_at' => now(),
            ]);

            Instruktur::create([
                'user_id' => $user->id,
                'nama_instr' => $validated['nama_instr'],
                'spesialisasi' => $validated['spesialisasi'],
            ]);
        });

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil ditambahkan');
    }

    public function edit(Instruktur $instruktur)
    {
        return view('instruktur::admin.instruktur.edit', compact('instruktur'));
    }

    public function update(Request $request, Instruktur $instruktur)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($instruktur->user_id)],
            'nama_instr' => 'required|string|max:255',
            'spesialisasi' => 'required|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $instruktur) {
            $instruktur->user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
            ]);

            $instruktur->update([
                'nama_instr' => $validated['nama_instr'],
                'spesialisasi' => $validated['spesialisasi'],
            ]);
        });

        return redirect()->route('admin.instruktur.index')->with('success', 'Instruktur berhasil diupdate');
    }

    public function destroy(Instruktur $instruktur)
    {
        $instruktur->user->delete();

        return back()->with('success', 'Instruktur dihapus');
    }
}
