<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Certificate;

class UserCertificateController extends Controller
{
    public function index()
    {
        $peserta = \App\Models\Peserta::where('user_id', Auth::id())->first();
        $certificates = [];
        if ($peserta) {
            $certificates = Certificate::where('participant_id', $peserta->id)
                ->where('status', 'published')
                ->latest()
                ->get();
        }
        return view('profile.certificates', compact('certificates'));
    }

    public function download($id)
    {
        $peserta = \App\Models\Peserta::where('user_id', Auth::id())->first();
        $certificate = Certificate::where('id', $id)
            ->where('participant_id', $peserta ? $peserta->id : null)
            ->where('status', 'published')
            ->firstOrFail();
        $course = $certificate->course;
        $participant = $peserta;
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('user.certificates.pdf', compact('certificate', 'participant', 'course'));
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
