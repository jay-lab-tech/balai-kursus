<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursus;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');

        // map resource authorization to policy
        $this->authorizeResource(\App\Models\Jadwal::class, 'jadwal');
    }
    public function index(Kursus $kursus)
    {
        $jadwals = $kursus->jadwals()->latest()->get();
        return view('kursus::admin.jadwal.index', compact('kursus', 'jadwals'));
    }

    // Global jadwal list across all kursus
    public function indexAll()
    {
        $jadwals = Jadwal::with('kursus')->latest()->get();
        return view('kursus::admin.jadwal.all', compact('jadwals'));
    }

    public function create(Kursus $kursus)
    {
        return view('kursus::admin.jadwal.create', compact('kursus'));
    }

    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'pertemuan_ke' => 'nullable|integer',
            'tgl_pertemuan' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable'
        ]);

        Jadwal::create([
            'kursus_id' => $kursus->id,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tgl_pertemuan' => $request->tgl_pertemuan,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'created_by' => auth()->id()
        ]);

        // Also create a Risalah record linked to this jadwal so instruktur can fill absensi
        $jadwal = \App\Models\Jadwal::where('kursus_id', $kursus->id)
            ->latest('id')
            ->first();

        if ($jadwal) {
            \App\Models\Risalah::create([
                'kursus_id' => $kursus->id,
                'instruktur_id' => $kursus->instruktur_id,
                'jadwal_id' => $jadwal->id,
                'pertemuan_ke' => $jadwal->pertemuan_ke,
                'tgl_pertemuan' => $jadwal->tgl_pertemuan,
                'materi' => 'Auto created by admin'
            ]);
        }

        return redirect("/admin/kursus/{$kursus->id}/jadwal")->with('success', 'Jadwal ditambahkan');
    }

    public function edit(Kursus $kursus, Jadwal $jadwal)
    {
        return view('kursus::admin.jadwal.edit', compact('kursus', 'jadwal'));
    }

    public function update(Request $request, Kursus $kursus, Jadwal $jadwal)
    {
        $request->validate([
            'pertemuan_ke' => 'nullable|integer',
            'tgl_pertemuan' => 'required|date',
        ]);

        $jadwal->update($request->only(['pertemuan_ke','tgl_pertemuan','jam_mulai','jam_selesai']));

        return redirect("/admin/kursus/{$kursus->id}/jadwal")->with('success', 'Jadwal diperbarui');
    }

    public function destroy(Kursus $kursus, Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success','Jadwal dihapus');
    }
}
