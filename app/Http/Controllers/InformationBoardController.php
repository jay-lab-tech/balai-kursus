<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class InformationBoardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $today = today();
        $displayMode = $request->boolean('display');
        $now = now();

        $jadwals = Jadwal::query()
            ->with([
                'kursus.program',
                'kursus.level',
                'kursus.instrukturKursusLevels.instruktur',
                'lokasi',
                'kela',
            ])
            ->whereDate('tgl_pertemuan', $today)
            ->orderByRaw('jam_mulai IS NULL')
            ->orderBy('jam_mulai')
            ->orderBy('id')
            ->get()
            ->map(fn (Jadwal $jadwal) => $this->transformJadwal($jadwal, $now));

        return view('public.information-board', [
            'displayMode' => $displayMode,
            'generatedAt' => $now,
            'ongoingJadwals' => $jadwals->where('status_key', 'ongoing')->values(),
            'todayLabel' => $today->translatedFormat('l, j F Y'),
            'jadwals' => $jadwals,
        ]);
    }

    private function transformJadwal(Jadwal $jadwal, Carbon $now): Jadwal
    {
        $mulaiAt = $jadwal->jam_mulai
            ? Carbon::parse($jadwal->tgl_pertemuan->format('Y-m-d').' '.$jadwal->jam_mulai)
            : null;
        $selesaiAt = $jadwal->jam_selesai
            ? Carbon::parse($jadwal->tgl_pertemuan->format('Y-m-d').' '.$jadwal->jam_selesai)
            : null;

        $jadwal->status_key = 'upcoming';
        $jadwal->status_label = 'Akan mulai';

        if (! $mulaiAt || ! $selesaiAt) {
            $jadwal->status_key = 'unscheduled';
            $jadwal->status_label = 'Jam belum diatur';
        } elseif ($now->between($mulaiAt, $selesaiAt)) {
            $jadwal->status_key = 'ongoing';
            $jadwal->status_label = 'Sedang berlangsung';
        } elseif ($now->greaterThan($selesaiAt)) {
            $jadwal->status_key = 'finished';
            $jadwal->status_label = 'Selesai';
        }

        $jadwal->jam_label = $mulaiAt && $selesaiAt
            ? $mulaiAt->format('H:i').' - '.$selesaiAt->format('H:i')
            : 'Jam belum diatur';

        $jadwal->program_level_label = collect([
            $jadwal->kursus?->program?->nama,
            $jadwal->kursus?->level?->nama,
        ])->filter()->join(' - ');

        $jadwal->instruktur_label = $jadwal->kursus?->instrukturKursusLevels
            ? $jadwal->kursus->instrukturKursusLevels
                ->sortBy('assigned_at')
                ->map(fn ($assignment) => $assignment->instruktur?->nama_instr)
                ->filter()
                ->first()
            : null;

        $jadwal->instruktur_label = $jadwal->instruktur_label ?: 'Instruktur belum ditentukan';
        $jadwal->lokasi_label = $jadwal->lokasi?->nama ?: 'Lokasi belum ditentukan';
        $jadwal->kelas_label = $jadwal->kela?->nama ?: 'Kelas belum ditentukan';

        return $jadwal;
    }
}
