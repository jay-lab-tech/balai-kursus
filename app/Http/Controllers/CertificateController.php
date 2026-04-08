<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\Kursus;
use App\Models\Peserta;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateController extends Controller
{
    // ADMIN: List certificates
    public function index()
    {
        $certificates = Certificate::with(['course', 'participant'])->get();
        return view('admin.certificates.index', compact('certificates'));
    }

    // ADMIN: Show create form
    public function create()
    {
        $courses = Kursus::all();
        return view('admin.certificates.create', compact('courses'));
    }

    // ADMIN: Store certificate
    public function store(Request $request)
    {
        $request->validate([
            'certificate_name' => 'required|string',
            'certificate_image' => 'required|image',
            'course_id' => 'required|exists:kursuses,id',
            'participant_id' => 'required|exists:pesertas,id',
        ]);

        $path = $request->file('certificate_image')->store('certificates', 'public');

        $peserta = Peserta::find($request->participant_id);
        Certificate::create([
            'certificate_name' => $request->certificate_name,
            'certificate_image_path' => $path,
            'course_id' => $request->course_id,
            'participant_id' => $request->participant_id,
            'user_id' => $peserta ? $peserta->user_id : null,
            'status' => 'pending',
        ]);

        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dibuat!');
    }

    // ADMIN: Edit certificate
    public function edit($id)
    {
        $certificate = Certificate::findOrFail($id);
        $courses = Kursus::all();
        return view('admin.certificates.edit', compact('certificate', 'courses'));
    }

    // ADMIN: Update certificate
    public function update(Request $request, $id)
    {
        $certificate = Certificate::findOrFail($id);
        $request->validate([
            'certificate_name' => 'required|string',
            'course_id' => 'required|exists:kursuses,id',
            'participant_id' => 'required|exists:pesertas,id',
        ]);
        $data = [
            'certificate_name' => $request->certificate_name,
            'course_id' => $request->course_id,
            'participant_id' => $request->participant_id,
            'user_id' => Peserta::whereKey($request->participant_id)->value('user_id'),
        ];
        if ($request->hasFile('certificate_image')) {
            $path = $request->file('certificate_image')->store('certificates', 'public');
            $data['certificate_image_path'] = $path;
        }
        $certificate->update($data);
        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil diupdate!');
    }

    // ADMIN: Delete certificate
    public function destroy($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->delete();
        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dihapus!');
    }

    // ADMIN: Publish certificate
    public function publish($id)
    {
        $certificate = Certificate::findOrFail($id);
        $certificate->status = 'published';
        $certificate->save();
        return redirect()->route('admin.certificates.index')->with('success', 'Sertifikat berhasil dipublish!');
    }

    // USER: List certificates
    public function myCertificates()
    {
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->first();
        if (!$peserta) {
            return view('user.certificates.index', ['certificates' => collect()]);
        }

        $certificates = Certificate::where('participant_id', $peserta->id)
            ->where('status', 'published')
            ->with('course')
            ->get();
        return view('user.certificates.index', compact('certificates'));
    }

    // USER: Download certificate PDF
    public function download($id)
    {
        $certificate = Certificate::findOrFail($id);
        $user = auth()->user();
        $peserta = Peserta::where('user_id', $user->id)->first();
        if (!$peserta || $certificate->participant_id != $peserta->id || $certificate->status !== 'published') {
            abort(403);
        }
        $pdf = Pdf::loadView('user.certificates.pdf', [
            'certificate' => $certificate,
            'participant' => $peserta,
            'course' => $certificate->course,
        ]);
        return $pdf->download('sertifikat.pdf');
    }
}
