<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Public verification page accessed by code/QR.
     */
    public function verify($code)
    {
        $cert = Certificate::where('verification_code', $code)->first();
        if (!$cert) {
            abort(404, 'Sertifikat tidak ditemukan');
        }

        return view('certificates.verify', [
            'certificate' => $cert,
        ]);
    }

    /**
     * Download the PDF file (internal use).
     */
    public function download($id)
    {
        $cert = Certificate::findOrFail($id);

        if (!$cert->file_path || !Storage::disk('local')->exists($cert->file_path)) {
            abort(404);
        }
        return Storage::disk('local')->download($cert->file_path, $cert->no_sertifikat . '.pdf');
    }
}
