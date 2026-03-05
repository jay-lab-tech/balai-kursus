<?php

namespace App\Http\Controllers\Admin;

use App\Models\Certificate;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CertificateAdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    /**
     * List all certificates with filter & pagination.
     */
    public function index(Request $request)
    {
        $query = Certificate::query();

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by kursus
        if ($request->filled('kursus_id')) {
            $query->where('kursus_id', $request->kursus_id);
        }

        // Search by no_sertifikat or nama peserta
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('no_sertifikat', 'like', "%$search%")
                ->orWhereHas('peserta', function ($q) use ($search) {
                    $q->where('nama', 'like', "%$search%");
                });
        }

        $certificates = $query->with(['peserta', 'kursus'])
            ->latest()
            ->paginate(20);

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Show certificate details.
     */
    public function show(Certificate $certificate)
    {
        return view('admin.certificates.show', compact('certificate'));
    }

    /**
     * Show revoke form.
     */
    public function editRevoke(Certificate $certificate)
    {
        if ($certificate->status === 'revoked') {
            return back()->with('info', 'Sertifikat sudah dicabut sebelumnya.');
        }

        return view('admin.certificates.revoke', compact('certificate'));
    }

    /**
     * Revoke a certificate.
     */
    public function revoke(Request $request, Certificate $certificate)
    {
        $request->validate([
            'revoked_reason' => 'required|string|min:10|max:500',
        ]);

        $certificate->update([
            'status' => 'revoked',
            'revoked_reason' => $request->revoked_reason,
            'revoked_at' => now(),
            'revoked_by' => auth()->id(),
        ]);

        return redirect()->route('admin.certificates.show', $certificate)
            ->with('success', 'Sertifikat berhasil dicabut.');
    }

    /**
     * Regenerate a revoked or failed certificate.
     */
    public function regenerate(Certificate $certificate)
    {
        $certificate->update([
            'status' => 'queued',
            'file_path' => null,
            'generated_at' => null,
            'revoked_reason' => null,
            'revoked_at' => null,
            'revoked_by' => null,
        ]);

        \App\Jobs\GenerateCertificateJob::dispatch($certificate);

        return back()->with('success', 'Sertifikat dijadwalkan untuk di-generate ulang.');
    }

    /**
     * Manually issue certificate for a peserta & kursus.
     */
    public function create()
    {
        $peserta = \App\Models\Peserta::all();
        $kursus = \App\Models\Kursus::all();

        return view('admin.certificates.create', compact('peserta', 'kursus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'peserta_id' => 'required|exists:pesertas,id',
            'kursus_id' => 'required|exists:kursuses,id',
            'validity_days' => 'nullable|integer|min:1',
        ]);

        $expiresAt = $request->validity_days ? now()->addDays($request->validity_days) : null;

        $cert = Certificate::create([
            'peserta_id' => $request->peserta_id,
            'kursus_id' => $request->kursus_id,
            'issued_at' => now(),
            'expires_at' => $expiresAt,
            'validity_days' => $request->validity_days,
        ]);

        \App\Jobs\GenerateCertificateJob::dispatch($cert);

        return redirect()->route('admin.certificates.show', $cert)
            ->with('success', 'Sertifikat berhasil diterbitkan: ' . $cert->no_sertifikat);
    }
}
