<?php
namespace App\Http\Controllers;

use App\Models\Peserta;
use Illuminate\Support\Facades\Auth;
use App\Models\Certificate;
use Barryvdh\DomPDF\Facade\Pdf;

class UserCertificateController extends Controller
{
    public function index()
    {
        $peserta = \App\Models\Peserta::where('user_id', Auth::id())->first();
        if ($peserta) {
            $certificates = Certificate::where('participant_id', $peserta->id)
                ->where('status', 'published')
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
        $certificate = Certificate::where('id', $id)
            ->where('participant_id', $peserta ? $peserta->id : null)
            ->where('status', 'published')
            ->firstOrFail();
        $course = $certificate->course;
        $participant = $peserta;
        $pdf = Pdf::loadView('user.certificates.pdf', compact('certificate', 'participant', 'course'));
        return $pdf->download('certificate-'.$certificate->id.'.pdf');
    }

    public function detail($id)
    {
        $peserta = \App\Models\Peserta::where('user_id', Auth::id())->first();
        $certificate = Certificate::where('id', $id)
            ->where('participant_id', $peserta ? $peserta->id : null)
            ->where('status', 'published')
            ->firstOrFail();
        return view('profile.certificate-detail', compact('certificate'));
    }
}
