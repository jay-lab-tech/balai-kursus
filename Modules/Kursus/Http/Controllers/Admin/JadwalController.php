<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Kursus;
use App\Models\Lokasi;
use App\Models\Kela;
use App\Models\Hari;
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
        $jadwals = $kursus->jadwals()
            ->with('lokasi', 'kela', 'hari')
            ->latest('id')
            ->paginate(15);
        return view('kursus::admin.jadwal.index', compact('kursus', 'jadwals'));
    }

    // Global jadwal list across all kursus
    public function indexAll()
    {
        $jadwals = Jadwal::with('kursus', 'lokasi', 'kela', 'hari')
            ->latest('id')
            ->paginate(15);
        return view('kursus::admin.jadwal.all', compact('jadwals'));
    }

    public function create(Kursus $kursus)
    {
        $lokasis = Lokasi::all();
        $kelas = Kela::all();
        $haris = Hari::all();
        return view('kursus::admin.jadwal.create', compact('kursus', 'lokasis', 'kelas', 'haris'));
    }

    public function store(Request $request, Kursus $kursus)
    {
        $request->validate([
            'pertemuan_ke' => 'nullable|integer',
            'tgl_pertemuan' => 'required|date',
            'jam_mulai' => 'nullable',
            'jam_selesai' => 'nullable',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kela_id' => 'required|exists:kelas,id',
            'hari_id' => 'required|exists:haris,id'
        ]);

        Jadwal::create([
            'kursus_id' => $kursus->id,
            'pertemuan_ke' => $request->pertemuan_ke,
            'tgl_pertemuan' => $request->tgl_pertemuan,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'lokasi_id' => $request->lokasi_id,
            'kela_id' => $request->kela_id,
            'hari_id' => $request->hari_id,
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
        $lokasis = Lokasi::all();
        $kelas = Kela::all();
        $haris = Hari::all();
        return view('kursus::admin.jadwal.edit', compact('kursus', 'jadwal', 'lokasis', 'kelas', 'haris'));
    }

    public function update(Request $request, Kursus $kursus, Jadwal $jadwal)
    {
        $request->validate([
            'pertemuan_ke' => 'nullable|integer',
            'tgl_pertemuan' => 'required|date',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kela_id' => 'required|exists:kelas,id',
            'hari_id' => 'required|exists:haris,id'
        ]);

        $jadwal->update($request->only(['pertemuan_ke','tgl_pertemuan','jam_mulai','jam_selesai','lokasi_id','kela_id','hari_id']));

        return redirect("/admin/kursus/{$kursus->id}/jadwal")->with('success', 'Jadwal diperbarui');
    }

    public function destroy(Kursus $kursus, Jadwal $jadwal)
    {
        $jadwal->delete();
        return back()->with('success','Jadwal dihapus');
    }
}
