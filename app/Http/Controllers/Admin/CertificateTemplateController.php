<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CertificateTemplate;
use Illuminate\Http\Request;

class CertificateTemplateController extends Controller
{
    public function index()
    {
        $templates = CertificateTemplate::withCount('certificates')->latest()->paginate(10);

        return view('admin.certificate-templates.index', compact('templates'));
    }

    public function create()
    {
        return view('admin.certificate-templates.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateData($request);

        if (! empty($data['is_active'])) {
            CertificateTemplate::query()->update(['is_active' => false]);
        }

        CertificateTemplate::create($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template sertifikat berhasil dibuat.');
    }

    public function edit(CertificateTemplate $template)
    {
        return view('admin.certificate-templates.edit', compact('template'));
    }

    public function update(Request $request, CertificateTemplate $template)
    {
        $data = $this->validateData($request);

        if (! empty($data['is_active'])) {
            CertificateTemplate::query()->whereKeyNot($template->id)->update(['is_active' => false]);
        }

        $template->update($data);

        return redirect()->route('admin.templates.index')->with('success', 'Template sertifikat berhasil diperbarui.');
    }

    public function destroy(CertificateTemplate $template)
    {
        if ($template->is_active) {
            return back()->with('success', 'Template aktif tidak bisa dihapus sebelum ada template aktif pengganti.');
        }

        $template->delete();

        return redirect()->route('admin.templates.index')->with('success', 'Template sertifikat berhasil dihapus.');
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'name' => 'required|string|max:255',
            'institution_name' => 'required|string|max:255',
            'unit_name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'header_logo_path' => 'nullable|string|max:255',
            'background_image_path' => 'nullable|string|max:255',
            'signature_image_path' => 'nullable|string|max:255',
            'stamp_image_path' => 'nullable|string|max:255',
            'signer_name' => 'required|string|max:255',
            'signer_title' => 'required|string|max:255',
            'signer_nip' => 'required|string|max:255',
            'certificate_prefix' => 'required|string|max:255',
            'is_active' => 'nullable|boolean',
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }
}
