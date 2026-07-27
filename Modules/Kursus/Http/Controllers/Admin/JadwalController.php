<?php

namespace Modules\Kursus\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hari;
use App\Models\InstrukturKursusLevel;
use App\Models\Jadwal;
use App\Models\Kela;
use App\Models\Kursus;
use App\Models\Lokasi;
use App\Models\Risalah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        $jadwals = Jadwal::with('kursus.program', 'lokasi', 'kela', 'hari')
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
        $validated = $this->validateJadwal($request);

        $jadwal = Jadwal::create([
            'kursus_id' => $kursus->id,
            'pertemuan_ke' => $validated['pertemuan_ke'] ?? null,
            'tgl_pertemuan' => $validated['tgl_pertemuan'],
            'jam_mulai' => $validated['jam_mulai'] ?? null,
            'jam_selesai' => $validated['jam_selesai'] ?? null,
            'lokasi_id' => $validated['lokasi_id'],
            'kela_id' => $validated['kela_id'],
            'hari_id' => $validated['hari_id'],
            'created_by' => auth()->id(),
        ]);

        $this->syncRisalahFromJadwal($kursus, $jadwal);

        return redirect()->route('admin.jadwal.index', $kursus)->with('success', 'Jadwal ditambahkan');
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
        $validated = $this->validateJadwal($request, $jadwal);

        $jadwal->update($validated);

        $this->syncRisalahFromJadwal($kursus, $jadwal);

        return redirect()->route('admin.jadwal.index', $kursus)->with('success', 'Jadwal diperbarui');
    }

    public function destroy(Kursus $kursus, Jadwal $jadwal)
    {
        Risalah::where('jadwal_id', $jadwal->id)->delete();
        $jadwal->delete();

        return back()->with('success', 'Jadwal dihapus');
    }

    private function syncRisalahFromJadwal(Kursus $kursus, Jadwal $jadwal): void
    {
        $instrukturId = InstrukturKursusLevel::where('kursus_id', $kursus->id)
            ->orderBy('id')
            ->value('instruktur_id');

        if (! $instrukturId) {
            return;
        }

        Risalah::updateOrCreate([
            'jadwal_id' => $jadwal->id,
        ], [
            'kursus_id' => $kursus->id,
            'instruktur_id' => $instrukturId,
            'pertemuan_ke' => $jadwal->pertemuan_ke,
            'tgl_pertemuan' => $jadwal->tgl_pertemuan,
            'materi' => 'Materi pertemuan '.($jadwal->pertemuan_ke ?: '-'),
            'catatan' => null,
        ]);
    }

    private function validateJadwal(Request $request, ?Jadwal $jadwal = null): array
    {
        $validator = Validator::make($request->all(), [
            'pertemuan_ke' => 'nullable|integer|min:1',
            'tgl_pertemuan' => 'required|date',
            'jam_mulai' => 'nullable|date_format:H:i',
            'jam_selesai' => 'nullable|date_format:H:i|after:jam_mulai',
            'lokasi_id' => 'required|exists:lokasis,id',
            'kela_id' => 'required|exists:kelas,id',
            'hari_id' => 'required|exists:haris,id',
        ], [
            'jam_mulai.date_format' => 'Jam mulai harus memakai format HH:MM.',
            'jam_selesai.date_format' => 'Jam selesai harus memakai format HH:MM.',
            'jam_selesai.after' => 'Jam selesai harus lebih besar dari jam mulai.',
        ]);

        $validator->after(function ($validator) use ($request, $jadwal) {
            $jamMulai = $request->input('jam_mulai');
            $jamSelesai = $request->input('jam_selesai');

            if (($jamMulai && ! $jamSelesai) || (! $jamMulai && $jamSelesai)) {
                $validator->errors()->add('jam_mulai', 'Jam mulai dan jam selesai harus diisi bersamaan.');

                return;
            }

            if (! $jamMulai || ! $jamSelesai || $validator->errors()->isNotEmpty()) {
                return;
            }

            $jadwalBentrok = Jadwal::query()
                ->with(['kursus', 'lokasi'])
                ->conflictingSlot(
                    (int) $request->input('lokasi_id'),
                    (string) $request->input('tgl_pertemuan'),
                    $jamMulai,
                    $jamSelesai,
                    $jadwal?->id
                )
                ->first();

            if (! $jadwalBentrok) {
                return;
            }

            $validator->errors()->add(
                'jam_mulai',
                sprintf(
                    'Jadwal bentrok dengan kelas %s di %s pada %s pukul %s-%s.',
                    $jadwalBentrok->kursus?->nama ?? 'lain',
                    $jadwalBentrok->lokasi?->nama ?? 'lokasi yang sama',
                    $jadwalBentrok->tgl_pertemuan?->format('d/m/Y') ?? '-',
                    substr((string) $jadwalBentrok->jam_mulai, 0, 5),
                    substr((string) $jadwalBentrok->jam_selesai, 0, 5)
                )
            );
        });

        return $validator->validate();
    }
}
