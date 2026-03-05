<?php

namespace App\Http\Controllers\Admin;

use App\Models\Certificate;
use App\Models\Kursus;
use App\Models\Peserta;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Jobs\GenerateCertificateJob;

class CertificateBatchController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * Show batch issue form (CSV upload or peserta multi-select).
     */
    public function create()
    {
        $kursus = Kursus::all();
        $peserta = Peserta::all();

        return view('admin.certificates.batch-create', compact('kursus', 'peserta'));
    }

    /**
     * Process batch issue from CSV or multi-select.
     */
    public function store(Request $request)
    {
        $request->validate([
            'kursus_id' => 'required|exists:kursuses,id',
            'peserta_ids' => 'required|array|min:1',
            'peserta_ids.*' => 'exists:pesertas,id',
            'validity_days' => 'nullable|integer|min:1',
            'csv_file' => 'nullable|file|mimes:csv,txt',
        ]);

        $kursusId = $request->kursus_id;
        $validityDays = $request->validity_days;
        $pesertaIds = $request->peserta_ids ?? [];

        // Parse CSV if uploaded
        if ($request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $content = file_get_contents($file->path());
            $lines = array_filter(explode("\n", $content));
            
            foreach ($lines as $line) {
                $id = trim($line);
                if (is_numeric($id) && !in_array($id, $pesertaIds)) {
                    $pesertaIds[] = $id;
                }
            }
        }

        $issued = 0;
        $failed = 0;

        foreach ($pesertaIds as $pesertaId) {
            try {
                // Check if not already issued
                $existing = Certificate::where('peserta_id', $pesertaId)
                    ->where('kursus_id', $kursusId)
                    ->where('status', '!=', 'revoked')
                    ->first();

                if ($existing) {
                    $failed++;
                    continue;
                }

                $expiresAt = $validityDays ? now()->addDays($validityDays) : null;

                $cert = Certificate::create([
                    'peserta_id' => $pesertaId,
                    'kursus_id' => $kursusId,
                    'issued_at' => now(),
                    'expires_at' => $expiresAt,
                    'validity_days' => $validityDays,
                    'status' => 'generated',
                ]);

                GenerateCertificateJob::dispatch($cert);
                $issued++;
            } catch (\Exception $e) {
                $failed++;
            }
        }

        $message = "✅ {$issued} sertifikat berhasil diterbitkan";
        if ($failed > 0) {
            $message .= " ({$failed} gagal/duplikat)";
        }

        return redirect()->route('admin.certificates.index')
            ->with('success', $message);
    }
}
