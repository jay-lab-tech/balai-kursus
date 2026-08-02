<?php

namespace Modules\Peserta\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PesertaController extends Controller
{
    /**
     * Export data peserta ke CSV
     */
    public function export()
    {
        $date = date('Ymd_His');
        $filename = "balai_kursus_upi_peserta_{$date}.xlsx";

        return \Maatwebsite\Excel\Facades\Excel::download(new \App\Exports\PesertaExport, $filename);
    }

    public function index()
    {
        $search = trim((string) request('search'));

        $pesertas = Peserta::with('user')
            // Seluruh kondisi pencarian dibungkus satu grup supaya `or` di
            // dalamnya tidak melebar ke kondisi lain di query.
            ->when($search !== '', fn ($query) => $query->where(function ($group) use ($search) {
                $group->whereHas('user', fn ($u) => $u
                    ->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%"))
                    ->orWhere('nomor_peserta', 'like', "%{$search}%")
                    ->orWhere('no_hp', 'like', "%{$search}%")
                    ->orWhere('instansi', 'like', "%{$search}%");
            }))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('peserta::admin.peserta.index', compact('pesertas', 'search'));
    }

    public function create()
    {
        return view('peserta::admin.peserta.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
            'nomor_peserta' => 'required|unique:pesertas',
            'no_hp' => 'required',
        ]);

        DB::transaction(function () use ($request) {

            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => 'peserta',
            ]);

            Peserta::create([
                'user_id' => $user->id,
                'nomor_peserta' => $request->nomor_peserta,
                'no_hp' => $request->no_hp,
                'instansi' => $request->instansi,
            ]);
        });

        return redirect('/admin/peserta')->with('success', 'Peserta berhasil ditambahkan');
    }

    public function edit($id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);

        return view('peserta::admin.peserta.edit', compact('peserta'));
    }

    public function update(Request $request, $id)
    {
        $peserta = Peserta::with('user')->findOrFail($id);

        // Tanpa validasi, email atau nomor peserta ganda baru ketahuan sebagai
        // galat basis data. Keunikan diperiksa dengan mengecualikan baris ini.
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$peserta->user_id,
            'nomor_peserta' => 'required|string|max:255|unique:pesertas,nomor_peserta,'.$peserta->id,
            'no_hp' => 'required|string|max:30',
            'instansi' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($peserta, $data) {
            $peserta->user->update([
                'name' => $data['nama'],
                'email' => $data['email'],
            ]);

            $peserta->update([
                'nomor_peserta' => $data['nomor_peserta'],
                'no_hp' => $data['no_hp'],
                'instansi' => $data['instansi'] ?? null,
            ]);
        });

        return redirect()->route('admin.peserta.index')
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $peserta = Peserta::findOrFail($id);
        $peserta->user->delete(); // otomatis hapus peserta karena cascade

        return redirect('/admin/peserta')->with('success', 'Peserta berhasil dihapus');
    }
}
