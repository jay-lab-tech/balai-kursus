<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\CertificateTemplate;
use App\Models\Kursus;
use App\Models\Pendaftaran;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CertificateController extends Controller
{
    public function index()
    {
        $certificates = Certificate::with(['course.program', 'participant.user', 'template'])->latest()->get();

        return view('admin.certificates.index', compact('certificates'));
    }

    /**
     * Halaman publik untuk memverifikasi keaslian sertifikat berdasarkan
     * nomor seri atau nomor sertifikat. Tidak memerlukan login.
     */
    public function verify($code)
    {
        $certificate = Certificate::with(['course.program', 'template'])
            ->where('serial_number', $code)
            ->orWhere('certificate_number', $code)
            ->first();

        $valid = $certificate && $certificate->status === Certificate::STATUS_PUBLISHED;

        return view('certificates.verify', [
            'code' => $code,
            'certificate' => $certificate,
            'valid' => $valid,
        ]);
    }

    public function create()
    {
        $courses = Kursus::with('program')->orderBy('nama')->get();
        $activeTemplate = CertificateTemplate::active()->first();

        return view('admin.certificates.create', compact('courses', 'activeTemplate'));
    }

    public function batchCreate()
    {
        $courses = Kursus::with('program')->orderBy('nama')->get();
        $activeTemplate = CertificateTemplate::active()->first();

        return view('admin.certificates.batch', compact('courses', 'activeTemplate'));
    }

    public function getParticipants(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:kursuses,id',
        ]);

        $participants = Pendaftaran::query()
            ->with('peserta.user')
            ->where('kursus_id', $validated['course_id'])
            ->whereIn('status_pendaftaran', [Pendaftaran::STATUS_AKTIF, Pendaftaran::STATUS_SELESAI])
            ->latest('id')
            ->get()
            ->filter(fn (Pendaftaran $pendaftaran) => $pendaftaran->peserta && $pendaftaran->peserta->user)
            ->unique('peserta_id')
            ->values()
            ->map(function (Pendaftaran $pendaftaran) {
                $peserta = $pendaftaran->peserta;

                return [
                    'id' => $peserta->id,
                    'nomor_peserta' => $peserta->nomor_peserta,
                    'nama' => $peserta->user->name,
                ];
            });

        return response()->json($participants);
    }

    public function store(Request $request)
    {
        $data = $this->validateCertificateRequest($request);
        $template = $this->requireActiveTemplate();
        $course = Kursus::with('program')->findOrFail($data['course_id']);
        $participant = Peserta::with('user')->findOrFail($data['participant_id']);

        $certificate = new Certificate;
        $certificate->fill([
            'certificate_name' => $data['certificate_name'],
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $participant->user_id,
            'template_id' => $template->id,
            'issued_date' => $data['issued_date'],
            'status' => Certificate::STATUS_DRAFT,
        ]);

        $this->applyCertificateSnapshot($certificate, $course, $participant, $template, true);
        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Draft sertifikat berhasil dibuat.');
    }

    public function batchStore(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:kursuses,id',
            'issued_date' => 'required|date',
            'certificate_name' => 'nullable|string|max:255',
            'registration_status' => 'nullable|in:selesai,aktif,aktif_selesai',
            'payment_status' => 'nullable|in:lunas,all',
            'min_attendance_percent' => 'nullable|numeric|min:0|max:100',
        ]);

        $template = $this->requireActiveTemplate();
        $course = Kursus::with('program')->findOrFail($data['course_id']);
        $registrations = $this->eligibleRegistrations($course->id, $data);

        $created = 0;
        $updated = 0;
        $skipped = 0;

        foreach ($registrations as $registration) {
            $participant = $registration->peserta;

            if (! $participant || ! $participant->user) {
                continue;
            }

            $certificate = Certificate::query()
                ->where('course_id', $course->id)
                ->where('participant_id', $participant->id)
                ->first();

            if ($certificate && $certificate->status === Certificate::STATUS_PUBLISHED) {
                $skipped++;

                continue;
            }

            $isNew = ! $certificate;
            $oldIssuedDate = $certificate?->issued_date?->toDateString();
            $certificate ??= new Certificate;

            $certificate->fill([
                'certificate_name' => $data['certificate_name'] ?: ('Sertifikat '.$course->nama),
                'course_id' => $course->id,
                'participant_id' => $participant->id,
                'user_id' => $participant->user_id,
                'template_id' => $template->id,
                'issued_date' => $data['issued_date'],
                'status' => Certificate::STATUS_DRAFT,
            ]);

            $regenerateNumbers = $isNew || blank($certificate->certificate_number) || $oldIssuedDate !== $data['issued_date'];
            $this->applyCertificateSnapshot($certificate, $course, $participant, $template, $regenerateNumbers);
            $certificate->save();

            if ($isNew) {
                $created++;
            } else {
                $updated++;
            }
        }

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', "Batch draft selesai. {$created} dibuat, {$updated} diperbarui, {$skipped} published dilewati.");
    }

    public function batchPublish(Request $request)
    {
        $data = $request->validate([
            'course_id' => 'required|exists:kursuses,id',
        ]);

        $certificates = Certificate::with(['course.program', 'participant.user', 'template'])
            ->where('course_id', $data['course_id'])
            ->where('status', Certificate::STATUS_DRAFT)
            ->get();

        $published = 0;

        foreach ($certificates as $certificate) {
            if (! $certificate->course || ! $certificate->participant) {
                continue;
            }

            $template = $certificate->template ?: $this->requireActiveTemplate();
            $this->applyCertificateSnapshot($certificate, $certificate->course, $certificate->participant, $template, false);
            $certificate->status = Certificate::STATUS_PUBLISHED;
            $certificate->save();
            $published++;
        }

        return redirect()
            ->route('admin.certificates.index')
            ->with('success', $published > 0
                ? "Publish massal selesai. {$published} sertifikat berhasil dipublish."
                : 'Tidak ada draft sertifikat yang bisa dipublish untuk kursus tersebut.');
    }

    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        $courses = Kursus::with('program')->orderBy('nama')->get();
        $activeTemplate = CertificateTemplate::active()->first();

        return view('admin.certificates.edit', compact('certificate', 'courses', 'activeTemplate'));
    }

    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        $data = $this->validateCertificateRequest($request);
        $template = $this->requireActiveTemplate();
        $course = Kursus::with('program')->findOrFail($data['course_id']);
        $participant = Peserta::with('user')->findOrFail($data['participant_id']);

        $certificate->fill([
            'certificate_name' => $data['certificate_name'],
            'course_id' => $course->id,
            'participant_id' => $participant->id,
            'user_id' => $participant->user_id,
            'template_id' => $template->id,
            'issued_date' => $data['issued_date'],
        ]);

        $this->applyCertificateSnapshot($certificate, $course, $participant, $template, ! filled($certificate->certificate_number));
        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Draft sertifikat berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dihapus.');
    }

    public function publish($id)
    {
        $certificate = Certificate::with(['course.program', 'participant.user'])->findOrFail($id);
        $template = $certificate->template ?: $this->requireActiveTemplate();

        $this->applyCertificateSnapshot($certificate, $certificate->course, $certificate->participant, $template, false);
        $certificate->status = Certificate::STATUS_PUBLISHED;
        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dipublish.');
    }

    public function revoke($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = Certificate::STATUS_REVOKED;
        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil direvoke.');
    }

    public function restoreDraft($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = Certificate::STATUS_DRAFT;
        $certificate->save();

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat dikembalikan ke draft untuk direvisi.');
    }

    public function preview($id)
    {
        $certificate = Certificate::with(['course.program', 'participant.user', 'template'])->findOrFail($id);

        return $this->buildCertificatePdf($certificate)->stream('certificate-preview-'.$certificate->id.'.pdf');
    }

    public function myCertificates()
    {
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->first();
        if (! $peserta) {
            return view('user.certificates.index', ['certificates' => collect()]);
        }

        $certificates = Certificate::where('participant_id', $peserta->id)
            ->where('status', Certificate::STATUS_PUBLISHED)
            ->with('course')
            ->get();

        return view('user.certificates.index', compact('certificates'));
    }

    public function download($id)
    {
        $certificate = Certificate::with(['course.program', 'participant.user', 'template'])->findOrFail($id);
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->first();

        if (! $peserta || $certificate->participant_id != $peserta->id || $certificate->status !== Certificate::STATUS_PUBLISHED) {
            abort(Response::HTTP_FORBIDDEN);
        }

        return $this->buildCertificatePdf($certificate, $peserta)->download('certificate-'.$certificate->id.'.pdf');
    }

    protected function validateCertificateRequest(Request $request): array
    {
        return $request->validate([
            'certificate_name' => 'required|string|max:255',
            'course_id' => 'required|exists:kursuses,id',
            'participant_id' => 'required|exists:pesertas,id',
            'issued_date' => 'required|date',
        ]);
    }

    protected function requireActiveTemplate(): CertificateTemplate
    {
        return CertificateTemplate::active()->firstOrFail();
    }

    protected function eligibleRegistrations(int $courseId, array $filters = [])
    {
        $query = Pendaftaran::query()
            ->with(['peserta.user', 'absensis'])
            ->where('kursus_id', $courseId)
            ->latest('id');

        $registrationStatus = $filters['registration_status'] ?? 'selesai';

        if ($registrationStatus === 'aktif_selesai') {
            $query->whereIn('status_pendaftaran', [Pendaftaran::STATUS_AKTIF, Pendaftaran::STATUS_SELESAI]);
        } else {
            $query->where('status_pendaftaran', $registrationStatus === 'aktif' ? Pendaftaran::STATUS_AKTIF : Pendaftaran::STATUS_SELESAI);
        }

        if (($filters['payment_status'] ?? 'lunas') === 'lunas') {
            $query->where('status_pembayaran', Pendaftaran::PAYMENT_LUNAS);
        }

        return $query
            ->get()
            ->filter(fn (Pendaftaran $pendaftaran) => $pendaftaran->peserta && $pendaftaran->peserta->user)
            ->filter(function (Pendaftaran $pendaftaran) use ($filters) {
                $minimumAttendance = $filters['min_attendance_percent'] ?? null;

                if ($minimumAttendance === null || $minimumAttendance === '') {
                    return true;
                }

                return $this->attendancePercentage($pendaftaran) >= (float) $minimumAttendance;
            })
            ->unique('peserta_id')
            ->values();
    }

    protected function attendancePercentage(Pendaftaran $pendaftaran): float
    {
        $absensis = $pendaftaran->absensis;

        if (! $absensis || $absensis->isEmpty()) {
            return 0;
        }

        $presentCount = $absensis->filter(function ($absensi) {
            $status = strtoupper((string) $absensi->status);

            return in_array($status, ['H', 'HADIR'], true);
        })->count();

        return round(($presentCount / $absensis->count()) * 100, 2);
    }

    protected function buildCertificatePdf(Certificate $certificate, ?Peserta $participant = null)
    {
        $participant ??= $certificate->participant;

        return Pdf::loadView('user.certificates.pdf', [
            'certificate' => $certificate,
            'participant' => $participant,
            'course' => $certificate->course,
            'template' => $certificate->template,
        ])->setPaper('a4', 'landscape');
    }

    protected function applyCertificateSnapshot(
        Certificate $certificate,
        Kursus $course,
        Peserta $participant,
        CertificateTemplate $template,
        bool $regenerateNumbers
    ): void {
        $issuedDate = $certificate->issued_date ?? now()->toDateString();
        $year = (int) date('Y', strtotime((string) $issuedDate));

        if ($regenerateNumbers || blank($certificate->serial_number)) {
            $nextSequence = Certificate::query()
                ->whereYear('issued_date', $year)
                ->whereKeyNot($certificate->id)
                ->count() + 1;

            $certificate->serial_number = str_pad((string) $nextSequence, 3, '0', STR_PAD_LEFT);
            $certificate->certificate_number = sprintf('%d/%s/%d', $nextSequence, $template->certificate_prefix, $year);
        }

        $certificate->template_id = $template->id;
        $certificate->participant_name_snapshot = $participant->user?->name;
        $certificate->program_name_snapshot = $course->program?->nama ?? $course->nama;
        $certificate->course_name_snapshot = $course->nama;
        $certificate->hours_snapshot = $course->jam_pelajaran;
        $certificate->start_date_snapshot = $course->tanggal_mulai;
        $certificate->end_date_snapshot = $course->tanggal_selesai;
        $certificate->signer_name_snapshot = $template->signer_name;
        $certificate->signer_title_snapshot = $template->signer_title;
        $certificate->signer_nip_snapshot = $template->signer_nip;
        $certificate->city_snapshot = $template->city;
    }
}
