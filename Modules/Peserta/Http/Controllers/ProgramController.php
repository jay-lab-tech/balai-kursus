<?php

namespace Modules\Peserta\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Pendaftaran;
use App\Models\Program;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class ProgramController extends Controller
{
    public function index()
    {
        $peserta = Auth::user()->peserta;

        $programs = Program::with([
            'kursuses' => function ($query) {
                $query->with('level')
                    ->withCount('pendaftarans')
                    ->orderBy('level_id')
                    ->orderBy('tanggal_mulai');
            },
        ])->get();

        $registrations = $peserta
            ? Pendaftaran::with(['program', 'level', 'kursus'])
                ->where('peserta_id', $peserta->id)
                ->latest('id')
                ->get()
                ->unique('program_id')
                ->keyBy('program_id')
            : collect();

        return view('peserta::program.index', compact('programs', 'registrations'));
    }

    public function show(Program $program)
    {
        $peserta = Auth::user()->peserta;

        $registration = $peserta
            ? Pendaftaran::with(['program', 'level', 'kursus', 'placementScore'])
                ->where('peserta_id', $peserta->id)
                ->where('program_id', $program->id)
                ->latest('id')
                ->first()
            : null;

        $program->load([
            'kursuses' => function ($query) {
                $query->with('level')
                    ->withCount('pendaftarans')
                    ->orderBy('level_id')
                    ->orderBy('tanggal_mulai');
            },
        ]);

        return view('peserta::program.show', compact('program', 'registration'));
    }

    public function daftar(Program $program): RedirectResponse
    {
        $peserta = Auth::user()->peserta;

        if (! $peserta) {
            abort(403, 'Akun ini belum memiliki profil peserta.');
        }

        $existingRegistration = Pendaftaran::query()
            ->where('peserta_id', $peserta->id)
            ->where('program_id', $program->id)
            ->whereNotIn('status_pendaftaran', [Pendaftaran::STATUS_SELESAI, Pendaftaran::STATUS_DIBATALKAN])
            ->exists();

        if ($existingRegistration) {
            return back()->with('error', 'Anda sudah memiliki pendaftaran aktif pada program ini.');
        }

        Pendaftaran::create([
            'peserta_id' => $peserta->id,
            'participant_email_snapshot' => Auth::user()->email,
            'program_id' => $program->id,
            'status_pendaftaran' => Pendaftaran::STATUS_MENUNGGU_TES,
            'status_pembayaran' => Pendaftaran::PAYMENT_PENDING,
            'total_bayar' => 0,
            'terbayar' => 0,
        ]);

        return redirect()->route('peserta.pendaftaran.index')
            ->with('success', 'Pendaftaran program berhasil. Ikuti tes penempatan, lalu admin akan memasukkan hasilnya.');
    }
}
