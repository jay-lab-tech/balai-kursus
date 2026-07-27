<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Peserta;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class UserCertificateController extends Controller
{
    public function index()
    {
        $peserta = Peserta::where('user_id', Auth::id())->first();
        if ($peserta) {
            $certificates = Certificate::where('participant_id', $peserta->id)
                ->where('status', Certificate::STATUS_PUBLISHED)
                ->latest()
                ->get();
        } else {
            $certificates = collect([]);
        }

        return view('profile.certificates', compact('certificates'));
    }

    public function download($id)
    {
        $peserta = Peserta::where('user_id', Auth::id())->first();
        $certificate = Certificate::with(['course.program', 'template'])
            ->where('id', $id)
            ->where('participant_id', $peserta ? $peserta->id : null)
            ->where('status', Certificate::STATUS_PUBLISHED)
            ->firstOrFail();
        $course = $certificate->course;
        $participant = $peserta;
        $template = $certificate->template;
        $pdf = Pdf::loadView('user.certificates.pdf', compact('certificate', 'participant', 'course', 'template'))
            ->setPaper('a4', 'landscape');

        return $pdf->download('certificate-'.$certificate->id.'.pdf');
    }

    public function detail($id)
    {
        $peserta = Peserta::where('user_id', Auth::id())->first();
        $certificate = Certificate::where('id', $id)
            ->where('participant_id', $peserta ? $peserta->id : null)
            ->where('status', Certificate::STATUS_PUBLISHED)
            ->firstOrFail();

        return view('profile.certificate-detail', compact('certificate'));
    }
}
